<?php

namespace App\Livewire;

use App\Models\FinancialRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FinanceStatisticsLivewire extends Component
{
    public $auth;
    public $selectedYear;
    public $selectedMonth;

    public function mount()
    {
        $this->auth = Auth::user();
        $this->selectedYear = date('Y');
        $this->selectedMonth = date('m');
    }

    public function render()
    {
        // Monthly data for the selected year
        $monthlyData = FinancialRecord::where('user_id', $this->auth->id)
            ->whereYear('transaction_date', $this->selectedYear)
            ->select(
                DB::raw('MONTH(transaction_date) as month'),
                DB::raw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as income'),
                DB::raw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as expense')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        // Fill missing months with zero
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = [
                'month' => $i,
                'income' => $monthlyData->get($i)->income ?? 0,
                'expense' => $monthlyData->get($i)->expense ?? 0,
            ];
        }

        // Category breakdown for selected month
        $categoryData = FinancialRecord::where('user_id', $this->auth->id)
            ->whereYear('transaction_date', $this->selectedYear)
            ->whereMonth('transaction_date', $this->selectedMonth)
            ->select('category', 'type', DB::raw('SUM(amount) as total'))
            ->groupBy('category', 'type')
            ->get();

        $incomeByCategory = $categoryData->where('type', 'income');
        $expenseByCategory = $categoryData->where('type', 'expense');

        // Overall statistics
        $totalIncome = FinancialRecord::where('user_id', $this->auth->id)
            ->where('type', 'income')
            ->sum('amount');

        $totalExpense = FinancialRecord::where('user_id', $this->auth->id)
            ->where('type', 'expense')
            ->sum('amount');

        $balance = $totalIncome - $totalExpense;

        // This month statistics
        $thisMonthIncome = FinancialRecord::where('user_id', $this->auth->id)
            ->where('type', 'income')
            ->whereYear('transaction_date', date('Y'))
            ->whereMonth('transaction_date', date('m'))
            ->sum('amount');

        $thisMonthExpense = FinancialRecord::where('user_id', $this->auth->id)
            ->where('type', 'expense')
            ->whereYear('transaction_date', date('Y'))
            ->whereMonth('transaction_date', date('m'))
            ->sum('amount');

        return view('livewire.finance-statistics-livewire', [
            'chartData' => $chartData,
            'incomeByCategory' => $incomeByCategory,
            'expenseByCategory' => $expenseByCategory,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'balance' => $balance,
            'thisMonthIncome' => $thisMonthIncome,
            'thisMonthExpense' => $thisMonthExpense,
        ]);
    }
}