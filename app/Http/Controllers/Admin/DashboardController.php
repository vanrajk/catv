<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\BillTransaction;
use App\Models\Customers;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Customers
        $totalCustomers = Customers::count();

        // Total Bills
        $totalBills = Bill::count();

        // Today's Bills
        $todayBills = Bill::whereDate('bill_date', today())->count();

        // Today's Transactions
        $todayTransactions = BillTransaction::whereDate('created_at', today())->count();

        // Today's Total Credit
        $todayCredit = BillTransaction::whereDate('created_at', today())
            ->sum(DB::raw("CASE 
                WHEN direction = 'bill_created' THEN after_credit
                WHEN direction = 'debit_to_credit' THEN amount
                ELSE 0 
            END"));

        // Today's Total Debit
        $todayDebit = BillTransaction::whereDate('created_at', today())
            ->sum(DB::raw("CASE 
                WHEN direction = 'bill_created' THEN after_debit
                WHEN direction = 'credit_to_debit' THEN amount
                ELSE 0 
            END"));

        // Total Outstanding Credit (across all bills)
        $totalCredit = Bill::sum('credit_amount');

        // Total Outstanding Debit (across all bills)
        $totalDebit = Bill::sum('debit_amount');

        // Recent Transactions (Last 10)
        $recentTransactions = BillTransaction::with(['bill.customer'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // This Month's Bills Count
        $thisMonthBills = Bill::whereMonth('bill_date', now()->month)
            ->whereYear('bill_date', now()->year)
            ->count();

        // This Month's Credit
        $thisMonthCredit = BillTransaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum(DB::raw("CASE 
                WHEN direction = 'bill_created' THEN after_credit
                WHEN direction = 'debit_to_credit' THEN amount
                ELSE 0 
            END"));

        // This Month's Debit
        $thisMonthDebit = BillTransaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum(DB::raw("CASE 
                WHEN direction = 'bill_created' THEN after_debit
                WHEN direction = 'credit_to_debit' THEN amount
                ELSE 0 
            END"));

        return view('admin.dashboard', compact(
            'totalCustomers',
            'totalBills',
            'todayBills',
            'todayTransactions',
            'todayCredit',
            'todayDebit',
            'totalCredit',
            'totalDebit',
            'recentTransactions',
            'thisMonthBills',
            'thisMonthCredit',
            'thisMonthDebit'
        ));
    }
}