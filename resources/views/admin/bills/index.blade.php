@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <!-- Filter -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">બિલ તારીખ</label>
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

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('bills') ? 'active' : '' }}"
               href="{{ route('bills', ['bill_date' => $date]) }}">
                <i class="bi bi-receipt"></i> બિલો
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('payments') ? 'active' : '' }}"
               href="{{ route('payments', ['bill_date' => $date]) }}">
                <i class="bi bi-cash-coin"></i> ચુકવણીઓ
            </a>
        </li>
    </ul>

    <!-- Bills Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-receipt"></i> બિલો (Created)
            </h5>
        </div>

        <div class="card-body table-responsive">
            
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>ગ્રાહક નં.</th>
                        <th>બિલ નં</th>
                        <th>નામ</th>
                        <th>અકાઉંટ</th>
                        <th>બિલ તારીખ</th>
                        <th class="text-end">જમા</th>
                        <th class="text-end">બાકી</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($bills as $bill)
                        <tr>
                            <td>{{ $loop->iteration + ($bills->currentPage() - 1) * $bills->perPage() }}</td>

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
                                દાખલ કરેલ તારીખ માટે કોઈ બિલો ઉપલબ્ધ નથી.
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