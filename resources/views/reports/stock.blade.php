@extends('ui.layouts.simple.master')

@section('title', 'Stock Report')
@section('breadcrumb-title', 'Stock Report')
@section('breadcrumb-items')
    <li class="breadcrumb-item active">Reports / Stock</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h5>Stock Report</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.stock') }}" class="mb-4">
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

                            <div class="col-md-9" style="padding-top: 30px;">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('reports.stock') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>

                    <div class="alert alert-info">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Total Books:</strong> {{ $summary['total_books'] }}
                            </div>
                            <div class="col-md-6">
                                <strong>Total Stock Value:</strong> {{ number_format($summary['total_stock_value'], 2) }}
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Book Title</th>
                                    <th>SKU</th>
                                    <th>Publisher</th>
                                    <th>Branch</th>
                                    <th>Quantity</th>
                                    <th>Cost Price</th>
                                    <th>Sell Price</th>
                                    <th>Stock Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($books as $book)
                                    @forelse($book->inventories as $inventory)
                                        <tr>
                                            <td>{{ $book->title }}</td>
                                            <td>{{ $book->sku ?? '-' }}</td>
                                            <td>{{ $book->publisher->name ?? '-' }}</td>
                                            <td>{{ $inventory->branch->name ?? '-' }}</td>
                                            <td>{{ $book->stockLabel($inventory->quantity) }}</td>
                                            <td>{{ number_format($book->cost_price, 2) }}</td>
                                            <td>{{ number_format($book->sell_price, 2) }}</td>
                                            <td>{{ number_format($inventory->quantity * $book->cost_price, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">No inventory</td>
                                        </tr>
                                    @endforelse
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No books found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $books->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
