@extends('ui.layouts.simple.master')

@section('title', 'Purchase Report')
@section('breadcrumb-title', 'Purchase Report')
@section('breadcrumb-items')
    <li class="breadcrumb-item active">Reports / Purchase</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h5>Purchase Report</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.purchase') }}" class="mb-4">
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
                                    <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                                    <option value="received" @selected(request('status') == 'received')>Received</option>
                                    <option value="cancelled" @selected(request('status') == 'cancelled')>Cancelled</option>
                                </select>
                            </div>

                            <div class="col-md-4" style="padding-top: 30px;">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('reports.purchase') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>

                    <div class="alert alert-info">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Total Purchases:</strong> {{ number_format($summary['total_purchases'], 2) }}
                            </div>
                            <div class="col-md-6">
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
                                    <th>Supplier</th>
                                    <th>Purchase Date</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($purchases as $purchase)
                                    <tr>
                                        <td>{{ $purchase->invoice_no }}</td>
                                        <td>{{ $purchase->branch->name ?? 'N/A' }}</td>
                                        <td>{{ $purchase->supplier->name ?? 'N/A' }}</td>
                                        <td>{{ $purchase->purchase_date->format('Y-m-d') }}</td>
                                        <td>{{ number_format($purchase->total_amount, 2) }}</td>
                                        <td>
                                            <span class="badge badge-{{ $purchase->status == 'received' ? 'success' : ($purchase->status == 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($purchase->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $purchase->user->name ?? 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('purchase.show', $purchase) }}" class="btn btn-sm btn-info">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No purchases found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $purchases->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
