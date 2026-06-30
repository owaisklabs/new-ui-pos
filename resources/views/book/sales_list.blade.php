@extends('ui.layouts.simple.master')
@section('title', 'Book Sales Detail')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>Book Sales Detail</h3>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $book->title }}</h5>
                <a href="{{ route('book.index') }}" class="btn btn-sm btn-secondary">Back to Items</a>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>SKU:</strong>
                        <p class="mb-0">{{ $book->sku }}</p>
                    </div>
                    <div class="col-md-3">
                        <strong>Bar Code:</strong>
                        <p class="mb-0">{{ $book->bar_code }}</p>
                    </div>
                    <div class="col-md-3">
                        <strong>Publisher:</strong>
                        <p class="mb-0">{{ $book->publisher->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-3">
                        <strong>Sell Price:</strong>
                        <p class="mb-0">{{ number_format($book->sell_price, 2) }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Authors:</strong>
                        <p class="mb-0">{{ $book->authors->pluck('name')->join(', ') ?: 'N/A' }}</p>
                    </div>
                    <div class="col-md-3">
                        <strong>Total Qty Sold:</strong>
                        <p class="mb-0">{{ $book->saleItems->sum('quantity') }}</p>
                    </div>
                    <div class="col-md-3">
                        <strong>Total Revenue:</strong>
                        <p class="mb-0">{{ number_format($book->saleItems->sum('total'), 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Sales History</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Invoice No</th>
                            <th>Sale Date</th>
                            <th>Customer</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Sale Price</th>
                            <th>Discount</th>
                            <th>Line Total</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($book->saleItems->sortByDesc(fn ($item) => $item->sale?->sale_date) as $index => $item)
                            @php
                                $sale = $item->sale;
                                $statusClass = [
                                    'open'      => 'bg-warning text-dark',
                                    'paid'      => 'bg-success',
                                    'cancelled' => 'bg-danger',
                                    'refunded'  => 'bg-orange text-white',
                                ][$sale->status ?? ''] ?? 'bg-secondary';
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $sale->invoice_no ?? '-' }}</td>
                                <td>{{ $sale->sale_date ?? '-' }}</td>
                                <td>{{ $sale->customer->name ?? 'N/A' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->unit_price, 2) }}</td>
                                <td>{{ number_format($item->sale_price, 2) }}</td>
                                <td>{{ $item->discount ?? 0 }}%</td>
                                <td>{{ number_format($item->total, 2) }}</td>
                                <td>
                                    @if($sale)
                                        <span class="badge rounded-pill {{ $statusClass }}">
                                            {{ ucfirst($sale->status) }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($sale)
                                        <a href="{{ route('sales.show', $sale->id) }}"
                                           class="btn btn-sm btn-primary p-1">
                                            <i data-feather="eye"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">No sales found for this book.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection
