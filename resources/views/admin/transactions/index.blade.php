@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <!-- Filter -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Bill Date</label>
                    <input type="date"
                           name="bill_date"
                           value="{{ $date }}"
                           class="form-control">
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                </div>

                <div class="col-md-3 text-muted">
                    Showing bills for <strong>{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</strong>
                </div>
            </form>
        </div>
    </div>

    <!-- Bills Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-receipt"></i> Bills
            </h5>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Customer No</th>
                        <th>Bill No</th>
                        <th>Name</th>
                        <th>Account</th>
                        <th>Bill Date</th>
                        <th class="text-end">Credit</th>
                        <th class="text-end">Debit</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($bills as $bill)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                {{ $bill->customer->customer_number ?? '-' }}
                            </td>

                            <td>
                                <strong>#{{ $bill->id }}</strong>
                            </td>

                            <td>
                                {{ $bill->customer->name ?? '-' }}
                            </td>

                            <td>
                                {{ $bill->customer->account_number ?? '-' }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($bill->bill_date)->format('d-m-Y') }}
                            </td>

                            <td class="text-end text-success fw-semibold">
                                ₹ {{ number_format($bill->credit_amount, 2) }}
                            </td>

                            <td class="text-end text-danger fw-semibold">
                                ₹ {{ number_format($bill->debit_amount, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                No bills found for selected date
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $bills->appends(request()->query())->links() }}
        </div>
    </div>

</div>
@endsection
