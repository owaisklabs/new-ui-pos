@extends('ui.layouts.simple.master')

@section('title', 'Branch Comparison Report')
@section('breadcrumb-title', 'Branch Comparison')
@section('breadcrumb-items')
    <li class="breadcrumb-item active">Reports / Branch Comparison</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h5>Branch Performance Comparison</h5>
                    <p class="text-muted">Compare sales, purchases, and expenses across all branches</p>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.branch-comparison') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="start_date">Start Date</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date', $startDate) }}">
                            </div>

                            <div class="col-md-3">
                                <label for="end_date">End Date</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date', $endDate) }}">
                            </div>

                            <div class="col-md-6" style="padding-top: 30px;">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('reports.branch-comparison') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Branch Name</th>
                                    <th>Branch Code</th>
                                    <th>Total Sales</th>
                                    <th>Total Purchases</th>
                                    <th>Total Expenses</th>
                                    <th>Net Profit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($branchData as $data)
                                    <tr>
                                        <td>
                                            <strong>{{ $data['branch']->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">{{ $data['branch']->code }}</span>
                                        </td>
                                        <td>
                                            <strong class="text-success">{{ number_format($data['sales'], 2) }}</strong>
                                        </td>
                                        <td>
                                            <span class="text-danger">{{ number_format($data['purchases'], 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="text-warning">{{ number_format($data['expenses'], 2) }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $profit = $data['sales'] - $data['purchases'] - $data['expenses'];
                                            @endphp
                                            <strong class="{{ $profit >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ number_format($profit, 2) }}
                                            </strong>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No branches available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="table-active">
                                    <td colspan="2"><strong>TOTAL</strong></td>
                                    <td>
                                        <strong class="text-success">
                                            {{ number_format($branchData->sum('sales'), 2) }}
                                        </strong>
                                    </td>
                                    <td>
                                        <strong class="text-danger">
                                            {{ number_format($branchData->sum('purchases'), 2) }}
                                        </strong>
                                    </td>
                                    <td>
                                        <strong class="text-warning">
                                            {{ number_format($branchData->sum('expenses'), 2) }}
                                        </strong>
                                    </td>
                                    <td>
                                        @php
                                            $totalProfit = $branchData->sum('sales') - $branchData->sum('purchases') - $branchData->sum('expenses');
                                        @endphp
                                        <strong class="{{ $totalProfit >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($totalProfit, 2) }}
                                        </strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mt-4">
        @forelse($branchData as $data)
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card border-left border-primary">
                    <div class="card-body">
                        <h6 class="card-title">{{ $data['branch']->name }}</h6>
                        <div class="mt-3">
                            <div class="mb-2">
                                <small class="text-muted">Sales:</small>
                                <strong class="text-success float-end">{{ number_format($data['sales'], 2) }}</strong>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">Purchases:</small>
                                <strong class="text-danger float-end">{{ number_format($data['purchases'], 2) }}</strong>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">Expenses:</small>
                                <strong class="text-warning float-end">{{ number_format($data['expenses'], 2) }}</strong>
                            </div>
                            <hr>
                            <div>
                                <small class="text-muted">Net Profit:</small>
                                @php
                                    $profit = $data['sales'] - $data['purchases'] - $data['expenses'];
                                @endphp
                                <strong class="float-end {{ $profit >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($profit, 2) }}
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-center text-muted">No branch data available</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
