@extends('ui.layouts.simple.master')

@section('title', 'Purchase Return Details')



@section('content')

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">Return Details — {{ $return_purchase->return_invoice_no }}</h5>

        <div>

            @if($return_purchase->purchase)

                <a href="{{ route('purchase.show', $return_purchase->purchase) }}" class="btn btn-secondary btn-sm">View Original Purchase</a>

            @endif

            <a href="{{ route('return-purchases.index') }}" class="btn btn-outline-secondary btn-sm">All Returns</a>

        </div>

    </div>



    <div class="card-body">

        @if(session('success'))

            <div class="alert alert-success">{{ session('success') }}</div>

        @endif



        <div class="row mb-3">

            <div class="col-md-4">

                <strong>Return Invoice:</strong>

                <p class="mb-0">{{ $return_purchase->return_invoice_no }}</p>

            </div>

            <div class="col-md-4">

                <strong>Original Purchase Invoice:</strong>

                <p class="mb-0">{{ $return_purchase->purchase->invoice_no ?? 'N/A' }}</p>

            </div>

            <div class="col-md-4">

                <strong>Supplier:</strong>

                <p class="mb-0">{{ $return_purchase->purchase->supplier->name ?? 'N/A' }}</p>

            </div>

        </div>



        <div class="row mb-3">

            <div class="col-md-4">

                <strong>Total Return Amount:</strong>

                <p class="mb-0">{{ number_format($return_purchase->total_return_amount, 2) }}</p>

            </div>

            <div class="col-md-4">

                <strong>Return Date:</strong>

                <p class="mb-0">{{ $return_purchase->created_at->format('d M Y H:i') }}</p>

            </div>

            <div class="col-md-4">

                <strong>Notes:</strong>

                <p class="mb-0">{{ $return_purchase->notes ?? '---' }}</p>

            </div>

        </div>



        <hr>



        <h5 class="mb-3">Returned Items</h5>



        <div class="table-responsive">

            <table class="table table-bordered table-striped align-middle">

                <thead class="table-light">

                    <tr>

                        <th>#</th>

                        <th>Book</th>

                        <th>Quantity</th>

                        <th>Unit Cost</th>

                        <th>Total</th>

                    </tr>

                </thead>

                <tbody>

                @forelse($return_purchase->items as $index => $item)

                    <tr>

                        <td>{{ $index + 1 }}</td>

                        <td>{{ $item->book->title ?? 'N/A' }}</td>

                        <td>{{ $item->quantity }}</td>

                        <td>{{ number_format($item->unit_cost, 2) }}</td>

                        <td>{{ number_format($item->total_price, 2) }}</td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5" class="text-center">No items in this return.</td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection

