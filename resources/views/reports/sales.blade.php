@extends('ui.layouts.simple.master')

@section('title', 'Sales Report')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header pb-0">
                <h5>Sales Report</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('reports.sales') }}" class="mb-4">
                    <div class="row">
                        <div class="col-md-2">
                            <label for="branch_id">Branch</label>
                            <select name="branch_id" id="branch_id" class="form-control">
                                <option value="">All Branches</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" @selected($branch->id == $branchId)>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="start_date">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>

                        <div class="col-md-2">
                            <label for="end_date">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>

                        <div class="col-md-2">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">All Status</option>
                                <option value="open" @selected(request('status') == 'open')>Open</option>
                                <option value="paid" @selected(request('status') == 'paid')>Paid</option>
                                <option value="refunded" @selected(request('status') == 'refunded')>Refunded</option>
                                <option value="cancelled" @selected(request('status') == 'cancelled')>Cancelled</option>
                            </select>
                        </div>

                        <div class="col-md-4" style="padding-top: 30px;">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('reports.sales') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>

                <div class="alert alert-info">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Total Sales:</strong> {{ number_format($summary['total_sales'], 2) }}
                        </div>
                        <div class="col-md-3">
                            <strong>Total Paid:</strong> {{ number_format($summary['total_paid'], 2) }}
                        </div>
                        <div class="col-md-3">
                            <strong>Outstanding:</strong> {{ number_format($summary['total_outstanding'], 2) }}
                        </div>
                        <div class="col-md-3">
                            <strong>Count:</strong> {{ $summary['count'] }}
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Invoice No</th>
                                <th>Branch</th>
                                <th>Customer</th>
                                <th>Sale Date</th>
                                <th>Total Amount</th>
                                <th>Paid Amount</th>
                                <th>Outstanding</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $sale)
                                <tr>
                                    <td>{{ $sale->invoice_no }}</td>
                                    <td>{{ $sale->branch->name ?? 'N/A' }}</td>
                                    <td>{{ $sale->customer->name ?? 'N/A' }}</td>
                                    <td>{{ $sale->sale_date->format('Y-m-d') }}</td>
                                    <td>{{ number_format($sale->total_amount, 2) }}</td>
                                    <td>{{ number_format($sale->paid_amount, 2) }}</td>
                                    <td>{{ number_format($sale->total_amount - $sale->paid_amount, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $sale->status == 'paid' ? 'success' : ($sale->status == 'open' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($sale->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-info">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No sales found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center">
                    {{ $sales->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
