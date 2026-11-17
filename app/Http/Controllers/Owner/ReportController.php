<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\ExpenseCategory;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->query('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', Carbon::now()->endOfMonth()->toDateString());

        $grossSales = 0.0;
        $totalTransactions = 0;
        $cogs = 0.0;

        // 1. Pengecekan Aman Tabel Transactions
        if (Schema::hasTable('transactions')) {
            $transactionsQuery = Transaction::whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate);

            $totalTransactions = $transactionsQuery->count();

            if (Schema::hasColumn('transactions', 'total_amount')) {
                $grossSales = (float) $transactionsQuery->sum('total_amount');
            } elseif (Schema::hasColumn('transactions', 'total')) {
                $grossSales = (float) $transactionsQuery->sum('total');
            }

            if (Schema::hasColumn('transactions', 'cogs_amount')) {
                $cogs = (float) $transactionsQuery->sum('cogs_amount');
            }
        }

        // 2. Deteksi Fleksibel Struktur Kolom Tabel Expenses
        $operatingExpenses = 0.0;
        $expenseDateCol = null;
        $expenseAmountCol = null;
        $expenseCategoryFk = null;

        if (class_exists(Expense::class) && Schema::hasTable('expenses')) {
            // Deteksi Nama Kolom Tanggal
            if (Schema::hasColumn('expenses', 'date')) {
                $expenseDateCol = 'date';
            } elseif (Schema::hasColumn('expenses', 'expense_date')) {
                $expenseDateCol = 'expense_date';
            } elseif (Schema::hasColumn('expenses', 'created_at')) {
                $expenseDateCol = 'created_at';
            }

            // Deteksi Nama Kolom Nominal/Jumlah
            if (Schema::hasColumn('expenses', 'amount')) {
                $expenseAmountCol = 'amount';
            } elseif (Schema::hasColumn('expenses', 'total')) {
                $expenseAmountCol = 'total';
            } elseif (Schema::hasColumn('expenses', 'nominal')) {
                $expenseAmountCol = 'nominal';
            }

            // Deteksi Foreign Key Kategori Pengeluaran
            if (Schema::hasColumn('expenses', 'expense_category_id')) {
                $expenseCategoryFk = 'expense_category_id';
            } elseif (Schema::hasColumn('expenses', 'category_id')) {
                $expenseCategoryFk = 'category_id';
            }

            // Hitung Total Pengeluaran Operasional
            if ($expenseAmountCol) {
                $expenseQuery = Expense::query();
                if ($expenseDateCol) {
                    $expenseQuery->whereDate($expenseDateCol, '>=', $startDate)
                                 ->whereDate($expenseDateCol, '<=', $endDate);
                }
                $operatingExpenses = (float) $expenseQuery->sum($expenseAmountCol);
            }
        }

        $grossProfit = $grossSales - $cogs;
        $netProfit = $grossProfit - $operatingExpenses;

        // 3. Kategori Pengeluaran (100% Ultra-Safe Fallback Verification)
        $expenseCategories = collect([]);
        if (
            class_exists(ExpenseCategory::class) && 
            Schema::hasTable('expense_categories') && 
            $expenseAmountCol && 
            $expenseCategoryFk && 
            method_exists(ExpenseCategory::class, 'expenses')
        ) {
            try {
                $expenseCategories = ExpenseCategory::withSum(['expenses as total_amount' => function ($query) use ($startDate, $endDate, $expenseDateCol) {
                    if ($expenseDateCol) {
                        $query->whereDate($expenseDateCol, '>=', $startDate)
                              ->whereDate($expenseDateCol, '<=', $endDate);
                    }
                }], $expenseAmountCol)->get();
            } catch (\Throwable $e) {
                $expenseCategories = collect([]);
            }
        }

        return view('owner.reports.index', compact(
            'grossSales',
            'totalTransactions',
            'cogs',
            'operatingExpenses',
            'grossProfit',
            'netProfit',
            'expenseCategories',
            'startDate',
            'endDate'
        ));
    }
}