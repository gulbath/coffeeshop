<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PosController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name', 'asc')->get();

        $products = Product::with(['category', 'recipes.ingredient'])
            ->where('status', 'active')
            ->orderBy('name', 'asc')
            ->get();

        return view('worker.pos', compact('categories', 'products'));
    }

    public function history(Request $request)
    {
        $search = trim($request->query('search', ''));

        $transactions = Transaction::with(['items.product', 'user'])
            ->when(!empty($search), function ($query) use ($search) {
                $query->where('transaction_number', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('worker.history', compact('transactions', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_method' => ['required', 'in:cash,qris,debit'],
            'cash_amount' => ['nullable', 'numeric', 'min:0'],
        ]);

        try {
            $transaction = DB::transaction(function () use ($validated, $request) {
                $subtotal = 0;
                $itemsToSave = [];
                $hasProductNameCol = Schema::hasColumn('transaction_items', 'product_name');
                $hasProductNameSnapshotCol = Schema::hasColumn('transaction_items', 'product_name_snapshot');

                foreach ($validated['items'] as $itemData) {
                    $product = Product::with('recipes.ingredient')->lockForUpdate()->find($itemData['id']);
                    $qty = $itemData['qty'];
                    $itemPrice = $product->selling_price;
                    $itemSubtotal = $itemPrice * $qty;
                    $subtotal += $itemSubtotal;

                    // Periksa & potong stok bahan baku sesuai resep
                    foreach ($product->recipes as $recipe) {
                        $ingredient = Ingredient::lockForUpdate()->find($recipe->ingredient_id);
                        $neededQty = $recipe->quantity_required * $qty;

                        if ($ingredient->current_stock < $neededQty) {
                            throw new \Exception("Stok bahan baku '{$ingredient->name}' tidak mencukupi untuk menu {$product->name}.");
                        }

                        $stockBefore = (float) $ingredient->current_stock;
                        $stockAfter = $stockBefore - $neededQty;
                        $ingredient->update(['current_stock' => $stockAfter]);

                        StockMovement::create([
                            'ingredient_id' => $ingredient->id,
                            'user_id' => $request->user()->id,
                            'type' => 'out',
                            'quantity' => $neededQty,
                            'stock_before' => $stockBefore,
                            'stock_after' => $stockAfter,
                            'notes' => "Pengurangan otomatis pesanan POS - {$qty}x {$product->name}",
                        ]);
                    }

                    $itemDataToPush = [
                        'product_id' => $product->id,
                        'unit_price' => $itemPrice,
                        'quantity' => $qty,
                        'subtotal' => $itemSubtotal,
                    ];

                    // Isi nama produk untuk kedua kemungkinan penamaan kolom di database
                    if ($hasProductNameCol) {
                        $itemDataToPush['product_name'] = $product->name;
                    }
                    if ($hasProductNameSnapshotCol) {
                        $itemDataToPush['product_name_snapshot'] = $product->name;
                    }

                    $itemsToSave[] = $itemDataToPush;
                }

                $discount = (float) ($validated['discount_amount'] ?? 0);
                $totalAmount = max(0, $subtotal - $discount);
                $cashAmount = $validated['payment_method'] === 'cash' ? (float) ($validated['cash_amount'] ?? $totalAmount) : $totalAmount;
                $changeAmount = max(0, $cashAmount - $totalAmount);

                if ($validated['payment_method'] === 'cash' && $cashAmount < $totalAmount) {
                    throw new \Exception("Uang tunai yang dibayarkan kurang dari total belanja.");
                }

                $trxData = [
                    'transaction_number' => 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
                    'user_id' => $request->user()->id,
                    'total_amount' => $totalAmount,
                ];

                if (Schema::hasColumn('transactions', 'subtotal')) {
                    $trxData['subtotal'] = $subtotal;
                }
                if (Schema::hasColumn('transactions', 'discount_amount')) {
                    $trxData['discount_amount'] = $discount;
                }
                if (Schema::hasColumn('transactions', 'cash_amount')) {
                    $trxData['cash_amount'] = $cashAmount;
                }
                if (Schema::hasColumn('transactions', 'change_amount')) {
                    $trxData['change_amount'] = $changeAmount;
                }
                if (Schema::hasColumn('transactions', 'payment_method')) {
                    $trxData['payment_method'] = $validated['payment_method'];
                }
                if (Schema::hasColumn('transactions', 'payment_status')) {
                    $trxData['payment_status'] = 'paid';
                }

                $trx = Transaction::create($trxData);

                foreach ($itemsToSave as &$item) {
                    $item['transaction_id'] = $trx->id;
                    $item['created_at'] = now();
                    $item['updated_at'] = now();
                }
                TransactionItem::insert($itemsToSave);

                return [
                    'transaction' => $trx,
                    'total_amount' => $totalAmount,
                    'change_amount' => $changeAmount,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diproses!',
                'transaction_number' => $transaction['transaction']->transaction_number,
                'total_amount' => $transaction['total_amount'],
                'change_amount' => $transaction['change_amount'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}