<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $ingredients = Ingredient::when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
            })
            ->orderBy('name', 'asc')
            ->paginate(10);

        return view('owner.ingredients.index', compact('ingredients', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:ingredients,code'],
            'name' => ['required', 'string', 'max:150'],
            'unit' => ['required', 'in:gram,ml,pcs'],
            'current_stock' => ['required', 'numeric', 'min:0'],
            'minimum_stock' => ['required', 'numeric', 'min:0'],
            'unit_cost' => ['required', 'numeric', 'min:0'],
        ], [
            'code.required' => 'Kode bahan baku wajib diisi.',
            'code.unique' => 'Kode bahan baku sudah terpakai.',
            'name.required' => 'Nama bahan baku wajib diisi.',
            'unit.required' => 'Satuan wajib dipilih.',
        ]);

        $validated['is_active'] = true;

        Ingredient::create($validated);

        return redirect()->route('owner.ingredients.index')
            ->with('success', 'Bahan baku baru berhasil ditambahkan.');
    }

    public function update(Request $request, Ingredient $ingredient)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:ingredients,code,' . $ingredient->id],
            'name' => ['required', 'string', 'max:150'],
            'unit' => ['required', 'in:gram,ml,pcs'],
            'current_stock' => ['required', 'numeric', 'min:0'],
            'minimum_stock' => ['required', 'numeric', 'min:0'],
            'unit_cost' => ['required', 'numeric', 'min:0'],
        ]);

        $ingredient->update($validated);

        return redirect()->route('owner.ingredients.index')
            ->with('success', 'Data bahan baku berhasil diperbarui.');
    }

    public function destroy(Ingredient $ingredient)
    {
        if ($ingredient->recipes()->count() > 0) {
            return redirect()->route('owner.ingredients.index')
                ->with('error', 'Bahan baku tidak dapat dihapus karena terikat pada resep produk.');
        }

        $ingredient->delete();

        return redirect()->route('owner.ingredients.index')
            ->with('success', 'Bahan baku berhasil dihapus.');
    }
}