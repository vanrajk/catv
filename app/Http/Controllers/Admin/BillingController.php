<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\BillTransaction;
use DB;

use App\Models\Bill;
use App\Models\Customers;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    /**
     * Get billing history of a customer (AJAX)
     */
public function customerBills(Customers $customer)
{
    $currentYear = now()->year;

    // Get all bills grouped by year
    $bills = Bill::where('customer_id', $customer->id)
        ->orderByDesc('bill_year')   // IMPORTANT: latest year first
        ->orderBy('bill_month')
        ->get()
        ->groupBy('bill_year');

    // Find earliest year (for showing older years)
    $firstYear = $bills->keys()->min() ?? $currentYear;

    // Build year → 12 months structure
    $years = [];

    for ($year = $currentYear; $year >= $firstYear; $year--) {
        $yearBills = $bills[$year] ?? collect();

        $months = collect(range(1, 12))->map(function ($month) use ($yearBills) {
            $bill = $yearBills->firstWhere('bill_month', $month);

          return [
                'bill_id' => $bill?->id,

    'month'  => $month,
    'name'   => date('M', mktime(0, 0, 0, $month, 1)),
    'credit' => $bill?->credit_amount,
    'debit'  => $bill?->debit_amount,
    'date'   => $bill?->bill_date,
];

        });

        $years[] = [
            'year'   => $year,
            'months' => $months,
        ];
    }

    return response()->json([
        'customer' => [
            'name'    => $customer->name,
            'account' => $customer->account_number,
            'mobile'  => $customer->mobile,
            'site'    => $customer->site->site_code ?? '-',
        ],
        'years' => $years
    ]);
}


    /**
     * Store a new bill (credit / debit)
     */
  public function store(Request $request)
{
    $request->validate([
        'customer_id'    => 'required|exists:customers,id',
        'bill_year'      => 'required|integer|min:2000',
        'bill_month'     => 'required|integer|between:1,12',
        'bill_date'      => 'required|date',
        'credit_amount'  => 'required|numeric|min:0',
        'debit_amount'   => 'required|numeric|min:0',
    ]);

    $exists = Bill::where('customer_id', $request->customer_id)
        ->where('bill_year', $request->bill_year)
        ->where('bill_month', $request->bill_month)
        ->exists();

    if ($exists) {
        return response()->json([
            'status' => false,
            'message' => 'Bill already exists for this month'
        ], 422);
    }

    return DB::transaction(function () use ($request) {

        // 1️⃣ Create Bill
        $bill = Bill::create([
            'customer_id'   => $request->customer_id,
            'bill_year'     => $request->bill_year,
            'bill_month'    => $request->bill_month,
            'bill_date'     => $request->bill_date,
            'credit_amount' => $request->credit_amount,
            'debit_amount'  => $request->debit_amount,
        ]);

        // 2️⃣ Create Opening Transaction
        BillTransaction::create([
            'bill_id'        => $bill->id,
            'direction'      => 'bill_created',
            'amount'         => max(
                $bill->credit_amount,
                $bill->debit_amount
            ),
            'before_debit'   => 0,
            'before_credit'  => 0,
            'after_debit'    => $bill->debit_amount,
            'after_credit'   => $bill->credit_amount,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Bill & transaction created successfully'
        ]);
    });
}

    /**
     * Delete bill (optional future use)
     */
    public function destroy(Bill $bill)
    {
        $bill->delete();

        return response()->json([
            'status' => true,
            'message' => 'Bill deleted'
        ]);
    }

    public function show(Customer $customer)
    {
        $bills = Bill::where('customer_id', $customer->id)
            ->orderBy('bill_year')
            ->orderBy('bill_month')
            ->get()
            ->groupBy('bill_year');

        return view('customers.partials.billing-modal', compact('customer', 'bills'));
    }

}
