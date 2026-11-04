@extends('ui.layouts.simple.master')

@section('title', 'Inventory Report')
@section('breadcrumb-title', 'Inventory Report')
@section('breadcrumb-items')
    <li class="breadcrumb-item active">Reports / Inventory</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h5>Inventory Report</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.inventory') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
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

                            <div class="col-md-3">
                                <label for="low_stock">Filter</label>
                                <div class="custom-control custom-checkbox mt-2">
                                    <input type="checkbox" name="low_stock" id="low_stock" class="custom-control-input" @checked($lowStock)>
                                    <label class="custom-control-label" for="low_stock">
                                        Low Stock Only
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6" style="padding-top: 30px;">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('reports.inventory') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>

                    <div class="alert alert-info">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Total Items:</strong> {{ $summary['total_items'] }}
                            </div>
                            <div class="col-md-4">
                                <strong>Total Value:</strong> {{ number_format($summary['total_value'], 2) }}
                            </div>
                            <div class="col-md-4">
                                <strong>Low Stock Items:</strong> {{ $summary['low_stock_count'] }}
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Book Title</th>
                                    <th>Branch</th>
                                    <th>Quantity</th>
                                    <th>Reorder Level</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inventory as $item)
                                    <tr>
                                        <td>{{ $item->book->title ?? 'N/A' }}</td>
                                        <td>{{ $item->branch->name ?? 'N/A' }}</td>
                                        <td>{{ $item->book ? $item->book->stockLabel($item->quantity) : $item->quantity }}</td>
                                        <td>{{ $item->reorder_level }}</td>
                                        <td>{{ $item->location ?? '-' }}</td>
                                        <td>
                                            @if($item->quantity <= $item->reorder_level)
                                                <span class="badge badge-danger">Low Stock</span>
                                            @else
                                                <span class="badge badge-success">In Stock</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No inventory found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $inventory->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
