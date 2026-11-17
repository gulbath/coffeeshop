<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Ingredient;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // 1. Stat Cards
        $todaySales = Transaction::whereDate('created_at', $today)
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        $todayTransactionsCount = Transaction::whereDate('created_at', $today)
            ->where('payment_status', 'paid')
            ->count();

        $monthlySales = Transaction::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        $monthlyExpenses = Expense::whereBetween('expense_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->sum('amount');

        // 2. Alert Bahan Baku Kritis (stok <= minimum_stock)
        $lowStockIngredients = Ingredient::where('is_active', true)
            ->whereColumn('current_stock', '<=', 'minimum_stock')
            ->orderBy('current_stock', 'asc')
            ->take(5)
            ->get();

        // 3. Produk Terlaris (Top 5 Menu via product_id)
        $topProducts = TransactionItem::select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_sales'))
            ->with('product')
            ->groupBy('product_id')
            ->orderBy('total_qty', 'desc')
            ->take(5)
            ->get();

        // 4. Transaksi Terbaru (Latest 5 Transactions)
        $recentTransactions = Transaction::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('owner.dashboard', compact(
            'todaySales',
            'todayTransactionsCount',
            'monthlySales',
            'monthlyExpenses',
            'lowStockIngredients',
            'topProducts',
            'recentTransactions'
        ));
    }
}