@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumb')
    <!-- <li class="breadcrumb-item active">Dashboard</li> -->
@endsection

@section('content')
    <!-- Statistics Cards Row 1 -->
    <div class="row mb-4">
        <!-- Total Customers -->
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-primary">
                <div class="inner">
                    <h3>{{ number_format($totalCustomers) }}</h3>
                    <p>કુલ ગ્રાહકો</p>
                </div>
                <div class="small-box-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <a href="{{ route('customers.index') }}" class="small-box-footer">
                    More info <i class="bi bi-chevron-right"></i>
                </a>
            </div>
        </div>

        <!-- Total Bills -->
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-success">
                <div class="inner">
                    <h3>{{ number_format($totalBills) }}</h3>
                    <p>કુલ બિલો</p>
                </div>
                <div class="small-box-icon">
                    <i class="bi bi-receipt-cutoff"></i>
                </div>
                <a href="{{ route('bills') }}" class="small-box-footer">
                    More info <i class="bi bi-chevron-right"></i>
                </a>
            </div>
        </div>

        <!-- Today's Bills -->
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-warning">
                <div class="inner">
                    <h3>{{ number_format($todayBills) }}</h3>
                    <p>આજ ના બિલો</p>
                </div>
                <div class="small-box-icon">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <a href="{{ route('bills', ['bill_date' => today()->toDateString()]) }}" class="small-box-footer">
                    More info <i class="bi bi-chevron-right"></i>
                </a>
            </div>
        </div>

        <!-- Today's Transactions -->
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-danger">
                <div class="inner">
                    <h3>{{ number_format($todayTransactions) }}</h3>
                    <p>આજ ની ચુકવણીઑ</p>
                </div>
                <div class="small-box-icon">
                    <i class="bi bi-arrow-left-right"></i>
                </div>
                <a href="{{ route('payments', ['bill_date' => today()->toDateString()]) }}" class="small-box-footer">
                    More info <i class="bi bi-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Financial Summary Row 2 -->
    <div class="row mb-4">
        <!-- Total Outstanding Credit -->
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-success">
                <div class="inner">
                    <h3>₹{{ number_format($totalCredit, 2) }}</h3>
                    <p>કુલ આવક</p>
                </div>
                <div class="small-box-icon">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <a href="{{ route('bills') }}" class="small-box-footer">
                    જોવો <i class="bi bi-chevron-right"></i>
                </a>
            </div>
        </div>

        <!-- Total Outstanding Debit -->
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-danger">
                <div class="inner">
                    <h3>₹{{ number_format($totalDebit, 2) }}</h3>
                    <p>કુલ બાકી</p>
                </div>
                <div class="small-box-icon">
                    <i class="bi bi-wallet2"></i>
                </div>
                <a href="{{ route('bills') }}" class="small-box-footer">
                    જોવો <i class="bi bi-chevron-right"></i>
                </a>
            </div>
        </div>

        <!-- Today's Credit -->
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-info">
                <div class="inner">
                    <h3>₹{{ number_format($todayCredit, 2) }}</h3>
                    <p>આજ ની આવક</p>
                </div>
                <div class="small-box-icon">
                    <i class="bi bi-arrow-down-circle"></i>
                </div>
                <a href="{{ route('payments', ['bill_date' => today()->toDateString()]) }}" class="small-box-footer">
                    ચુકવણીઑ જોવો <i class="bi bi-chevron-right"></i>
                </a>
            </div>
        </div>

        <!-- Today's Debit -->
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-secondary">
                <div class="inner">
                    <h3>₹{{ number_format($todayDebit, 2) }}</h3>
                    <p>આજ ના બાકી</p>
                </div>
                <div class="small-box-icon">
                    <i class="bi bi-arrow-up-circle"></i>
                </div>
                <a href="{{ route('payments', ['bill_date' => today()->toDateString()]) }}" class="small-box-footer">
                    ચુકવણી જોવો <i class="bi bi-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Monthly Summary Row 3 -->
    <div class="row mb-4">
        <!-- This Month's Bills -->
        <div class="col-lg-4 col-6">
            <div class="small-box" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="inner">
                    <h3>{{ number_format($thisMonthBills) }}</h3>
                    <p>આ મહિનાના બિલો ની સંખ્યા</p>
                </div>
                <div class="small-box-icon">
                    <i class="bi bi-calendar3"></i>
                </div>
                <a href="{{ route('bills') }}" class="small-box-footer" style="color: rgba(255,255,255,0.8);">
                    View Details <i class="bi bi-chevron-right"></i>
                </a>
            </div>
        </div>

        <!-- This Month's Credit -->
        <div class="col-lg-4 col-6">
            <div class="small-box" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                <div class="inner">
                    <h3>₹{{ number_format($thisMonthCredit, 2) }}</h3>
                    <p>આ મહિનાની કુલ આવક</p>
                </div>
                <div class="small-box-icon">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <a href="{{ route('payments') }}" class="small-box-footer" style="color: rgba(255,255,255,0.8);">
                    ચુકવણી જોવો <i class="bi bi-chevron-right"></i>
                </a>
            </div>
        </div>

        <!-- This Month's Debit -->
        <div class="col-lg-4 col-6">
            <div class="small-box" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                <div class="inner">
                    <h3>₹{{ number_format($thisMonthDebit, 2) }}</h3>
                    <p>આ મહિનાના કુલ બાકી</p>
                </div>
                <div class="small-box-icon">
                    <i class="bi bi-graph-down-arrow"></i>
                </div>
                <a href="{{ route('payments') }}" class="small-box-footer" style="color: rgba(255,255,255,0.8);">
                    ચુકવણી જોવો <i class="bi bi-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history"></i> હાલ ની ચુકવણીઑ
                    </h5>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>તારીખ અને સમય</th>
                                <th>બિલ નં.</th>
                                <th>ગ્રાહક</th>
                                <th>રીત</th>
                                <th class="text-end">જમા</th>
                                <th class="text-end">ઉધાર</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $transaction)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $transaction->created_at->format('d-m-Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('bills', ['bill_date' => $transaction->bill->bill_date->toDateString()]) }}" class="text-decoration-none">
                                            <strong>#{{ $transaction->bill_id }}</strong>
                                        </a>
                                    </td>
                                    <td>{{ $transaction->bill->customer->name ?? '-' }}</td>
                                    <td>
                                        @if($transaction->direction === 'bill_created')
                                            <span class="badge bg-primary">Bill Created</span>
                                        @elseif($transaction->direction === 'debit_to_credit')
                                            <span class="badge bg-success">Payment</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Reversal</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($transaction->direction === 'bill_created')
                                            <span class="text-success fw-bold">₹{{ number_format($transaction->after_credit, 2) }}</span>
                                        @elseif($transaction->direction === 'debit_to_credit')
                                            <span class="text-success fw-bold">₹{{ number_format($transaction->amount, 2) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($transaction->direction === 'bill_created')
                                            <span class="text-danger fw-bold">₹{{ number_format($transaction->after_debit, 2) }}</span>
                                        @elseif($transaction->direction === 'credit_to_debit')
                                            <span class="text-danger fw-bold">₹{{ number_format($transaction->amount, 2) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No recent transactions</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="text-end mt-3">
                        <a href="{{ route('payments') }}" class="btn btn-primary">
                            <i class="bi bi-eye"></i> View All Transactions
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection