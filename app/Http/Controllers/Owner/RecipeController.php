<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function show(Product $product)
    {
        $product->load(['recipes.ingredient', 'category']);
        $ingredients = Ingredient::where('is_active', true)->orderBy('name', 'asc')->get();

        // Hitung Total COGS / HPP berdasarkan resep
        $cogs = $product->recipes->sum(function ($recipe) {
            return $recipe->quantity_required * $recipe->ingredient->unit_cost;
        });

        return view('owner.recipes.show', compact('product', 'ingredients', 'cogs'));
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'ingredient_id' => ['required', 'exists:ingredients,id'],
            'quantity_required' => ['required', 'numeric', 'gt:0'],
        ], [
            'ingredient_id.required' => 'Pilih bahan baku.',
            'quantity_required.gt' => 'Jumlah takaran harus lebih dari 0.',
        ]);

        // Cegah bahan baku ganda pada produk yang sama
        Recipe::updateOrCreate(
            [
                'product_id' => $product->id,
                'ingredient_id' => $validated['ingredient_id'],
            ],
            [
                'quantity_required' => $validated['quantity_required'],
            ]
        );

        return redirect()->route('owner.products.recipes.show', $product)
            ->with('success', 'Bahan baku berhasil ditambahkan ke resep.');
    }

    public function destroy(Product $product, Recipe $recipe)
    {
        $recipe->delete();

        return redirect()->route('owner.products.recipes.show', $product)
            ->with('success', 'Bahan baku berhasil dihapus dari resep.');
    }
}