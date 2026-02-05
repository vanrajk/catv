@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <!-- Filter -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">બિલ ની તારીખ</label>
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
                    Showing payments for <strong>{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</strong>
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
                <i class="bi bi-cash-coin"></i> ચુકવણીઑ
            </a>
        </li>
    </ul>

    <!-- Payments Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-cash-coin"></i> બધી ચૂકવણીઓ
            </h5>
        </div>

        <div class="card-body table-responsive">
            
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>ચુકવણી ની તારીખ</th>
                        <th>ગ્રાહક નં.</th>
                        <th>બિલ નં</th>
                        <th>નામ</th>
                        <th>અકાઉંટ</th>
                        <th>ચુકવણી ની રીત</th>
                        <th class="text-end">જમા</th>
                        <th class="text-end">બાકી</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() }}</td>

                            <td>
                                {{ $transaction->created_at->format('d-m-Y H:i') }}
                            </td>

                            <td>
                                {{ $transaction->bill->customer->customer_number ?? '-' }}
                            </td>

                            <td>
                                <strong>#{{ $transaction->bill_id }}</strong>
                            </td>

                            <td>
                                {{ $transaction->bill->customer->name ?? '-' }}
                            </td>

                            <td>
                                {{ $transaction->bill->customer->account_number ?? '-' }}
                            </td>

                            <td>
                                @if($transaction->direction === 'bill_created')
                                    <span class="badge bg-primary">
                                        <i class="bi bi-receipt"></i> Bill Created
                                    </span>
                                @elseif($transaction->direction === 'debit_to_credit')
                                    <span class="badge bg-success">
                                        <i class="bi bi-arrow-right"></i> Payment (Debit → Credit)
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-arrow-left"></i> Reversal (Credit → Debit)
                                    </span>
                                @endif
                            </td>

                            {{-- Credit Column --}}
                            <td class="text-end">
                                @if($transaction->direction === 'bill_created')
                                    <span class="text-success fw-bold">
                                        ₹ {{ number_format($transaction->after_credit, 2) }}
                                    </span>
                                @elseif($transaction->direction === 'debit_to_credit')
                                    <span class="text-success fw-bold">
                                        ₹ {{ number_format($transaction->amount, 2) }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- Debit Column --}}
                            <td class="text-end">
                                @if($transaction->direction === 'bill_created')
                                    <span class="text-danger fw-bold">
                                        ₹ {{ number_format($transaction->after_debit, 2) }}
                                    </span>
                                @elseif($transaction->direction === 'credit_to_debit')
                                    <span class="text-danger fw-bold">
                                        ₹ {{ number_format($transaction->amount, 2) }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">
							દાખલ કરેલ તારીખ માટે કોઈ ચુકવણીઑ ઉપલબ્ધ નથી.

                            </td>
                        </tr>
                    @endforelse
                </tbody>

                <tfoot class="table-light">
                    <tr>
                        <th colspan="7" class="text-end">Page Total:</th>
                        <th class="text-end text-success fw-bold">
                            ₹ {{ number_format($totalCredit, 2) }}
                        </th>
                        <th class="text-end text-danger fw-bold">
                            ₹ {{ number_format($totalDebit, 2) }}
                        </th>
                    </tr>
                </tfoot>
            </table>

            {{ $transactions->appends(request()->query())->links() }}
        </div>
    </div>

</div>
@endsection