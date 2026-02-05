<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\BillTransaction;
use Illuminate\Http\Request;
use DB;

class BillTransactionController extends Controller
{
    /**
     * Display bills with 'bill_created' transactions only
     */
    public function index(Request $request)
    {
        // Default date = today
        $date = $request->get('bill_date', now()->toDateString());

        // Get bills with bill_created transactions
        $bills = Bill::with(['customer', 'transactions' => function($query) {
                $query->where('direction', 'bill_created')
                      ->orderByDesc('created_at');
            }])
            ->whereHas('transactions', function($query) {
                $query->where('direction', 'bill_created');
            })
            ->whereDate('bill_date', $date)
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.bills.index', compact('bills', 'date'));
    }

    /**
     * Display all transactions (bill_created, debit_to_credit, credit_to_debit)
     */
    public function payments(Request $request)
    {
        // Default date = today
        $date = $request->get('bill_date', now()->toDateString());

        // Get all transactions
        $transactions = BillTransaction::with(['bill.customer'])
            ->whereHas('bill', function($query) use ($date) {
                $query->whereDate('bill_date', $date);
            })
            ->orderByDesc('created_at')
            ->paginate(20);

        // Calculate totals for current page
        $totalCredit = 0;
        $totalDebit = 0;

        foreach($transactions as $transaction) {
            if($transaction->direction === 'bill_created') {
                $totalCredit += $transaction->after_credit;
                $totalDebit += $transaction->after_debit;
            } elseif($transaction->direction === 'debit_to_credit') {
                $totalCredit += $transaction->amount;
            } elseif($transaction->direction === 'credit_to_debit') {
                $totalDebit += $transaction->amount;
            }
        }

        return view('admin.bills.payments', compact('transactions', 'date', 'totalCredit', 'totalDebit'));
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'bill_id'   => 'required|exists:bills,id',
            'direction' => 'required|in:debit_to_credit,credit_to_debit',
            'amount'    => 'required|numeric|min:1',
        ]);

        return DB::transaction(function () use ($request) {

            $bill = Bill::lockForUpdate()->findOrFail($request->bill_id);

            $beforeDebit  = $bill->debit_amount;
            $beforeCredit = $bill->credit_amount;
            $amount       = $request->amount;

            if ($request->direction === 'debit_to_credit') {
                if ($amount > $beforeDebit) {
                    abort(422, 'Transfer amount exceeds debit balance');
                }

                $bill->debit_amount  -= $amount;
                $bill->credit_amount += $amount;
            } else {
                if ($amount > $beforeCredit) {
                    abort(422, 'Transfer amount exceeds credit balance');
                }

                $bill->credit_amount -= $amount;
                $bill->debit_amount  += $amount;
            }

            $bill->save();

            BillTransaction::create([
                'bill_id'        => $bill->id,
                'direction'      => $request->direction,
                'amount'         => $amount,
                'before_debit'   => $beforeDebit,
                'before_credit'  => $beforeCredit,
                'after_debit'    => $bill->debit_amount,
                'after_credit'   => $bill->credit_amount,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Transfer completed successfully'
            ]);
        });
    }

    public function byBill($billId)
    {
        $transactions = BillTransaction::where('bill_id', $billId)
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($tx) {

                if ($tx->direction === 'debit_to_credit') {
                    return [
                        'direction' => $tx->direction,
                        'label'     => 'Debit → Credit',
                        'before_debit'    => $tx->before_debit,
                        'after_debit'     => $tx->after_debit,
                        'amount'    => $tx->amount,
                        'date'      => $tx->created_at->format('d-m-Y'),
                    ];
                }

                if ($tx->direction === 'bill_created') {
                    return [
                        'direction' => $tx->direction,
                        'label'     => 'Bill Created',
                        'credit'    => $tx->after_credit,
                        'debit'     => $tx->after_debit,
                        'amount'    => $tx->amount,
                        'date'      => $tx->created_at->format('d-m-Y'),
                    ];
                }

                return [
                    'direction' => $tx->direction,
                    'label'     => 'Credit → Debit',
                    'before_credit'    => $tx->before_credit,
                    'after_credit'     => $tx->after_credit,
                    'amount'    => $tx->amount,
                    'date'      => $tx->created_at->format('d-m-Y'),
                ];
            });

        return response()->json($transactions);
    }
}