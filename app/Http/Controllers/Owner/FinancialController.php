<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FinancialController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->query('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Pemasukan (Incomes from Transactions)
        $incomes = Transaction::with('user')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('payment_status', 'paid')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'incomes_page');

        // Pengeluaran (Expenses)
        $expenses = Expense::with('user')
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->orderBy('expense_date', 'desc')
            ->paginate(10, ['*'], 'expenses_page');

        $totalIncome = Transaction::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        $totalExpense = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->sum('amount');

        $netCashFlow = $totalIncome - $totalExpense;

        return view('owner.financials.index', compact(
            'incomes',
            'expenses',
            'totalIncome',
            'totalExpense',
            'netCashFlow',
            'startDate',
            'endDate'
        ));
    }
}