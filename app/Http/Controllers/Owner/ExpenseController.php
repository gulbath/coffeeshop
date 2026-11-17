<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $category = $request->query('category');

        $expenses = Expense::with('user')
            ->when($search, function ($query, $search) {
                $query->where('notes', 'like', "%{$search}%")
                      ->orWhere('expense_number', 'like', "%{$search}%");
            })
            ->when($category, function ($query, $category) {
                $query->where('category', $category);
            })
            ->orderBy('expense_date', 'desc')
            ->paginate(10);

        return view('owner.expenses.index', compact('expenses', 'search', 'category'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => ['required', 'string', 'max:100'],
            'amount' => ['required', 'numeric', 'min:1'],
            'expense_date' => ['required', 'date'],
            'notes' => ['required', 'string', 'max:255'],
        ], [
            'category.required' => 'Kategori pengeluaran wajib diisi.',
            'amount.required' => 'Jumlah nominal pengeluaran wajib diisi.',
            'expense_date.required' => 'Tanggal pengeluaran wajib diisi.',
            'notes.required' => 'Keterangan pengeluaran wajib diisi.',
        ]);

        $validated['expense_number'] = 'EXP-' . date('Ymd') . '-' . strtoupper(Str::random(4));
        $validated['user_id'] = $request->user()->id;

        Expense::create($validated);

        return redirect()->route('owner.expenses.index')
            ->with('success', 'Catatan pengeluaran operasional berhasil disimpan.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('owner.expenses.index')
            ->with('success', 'Catatan pengeluaran berhasil dihapus.');
    }
}