<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $ingredientId = $request->query('ingredient_id');

        $mutations = StockMovement::with(['ingredient', 'user'])
            ->when($ingredientId, function ($query, $ingredientId) {
                $query->where('ingredient_id', $ingredientId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $ingredients = Ingredient::orderBy('name', 'asc')->get();

        return view('owner.inventory.index', compact('mutations', 'ingredients', 'ingredientId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ingredient_id' => ['required', 'exists:ingredients,id'],
            'type' => ['required', 'in:in,out,adjustment'],
            'quantity' => ['required', 'numeric', 'min:0.01'],
            'notes' => ['nullable', 'string'],
        ]);

        $ingredient = Ingredient::findOrFail($validated['ingredient_id']);
        $stockBefore = (float) $ingredient->current_stock;
        $qty = (float) $validated['quantity'];

        if ($validated['type'] === 'in') {
            $stockAfter = $stockBefore + $qty;
        } elseif ($validated['type'] === 'out') {
            if ($stockBefore < $qty) {
                return redirect()->back()->with('error', "Stok bahan baku tidak mencukupi untuk dikurangi.");
            }
            $stockAfter = $stockBefore - $qty;
        } else {
            $stockAfter = $qty;
        }

        $ingredient->update(['current_stock' => $stockAfter]);

        StockMovement::create([
            'ingredient_id' => $ingredient->id,
            'user_id' => $request->user()->id,
            'type' => $validated['type'],
            'quantity' => $qty,
            'stock_before' => $stockBefore,
            'stock_after' => $stockAfter,
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('owner.inventory.index')
            ->with('success', 'Mutasi stok bahan baku berhasil dicatat.');
    }
}