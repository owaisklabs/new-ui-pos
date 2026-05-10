@extends('ui.layouts.simple.master')
@section('title', 'Bootstrap Border Table')

@section('css')
@endsection

@section('style')
@endsection


@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Sales  Details</h5>
    </div>

    <div class="card-body">

        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Invoice No:</strong>
                <p class="mb-0">{{ $sale->invoice_no }}</p>
            </div>

            <div class="col-md-6">
                <strong>Sale Date:</strong>
                <p class="mb-0">{{ $sale->sale_date }}</p>
            </div>
        </div>

        <div class="row mb-3">

            <div class="col-md-6">
                <strong>Customer ID:</strong>
                <p class="mb-0">
                    {{ $sale->customer->name ?? 'N/A' }}
                </p>
            </div>

            <div class="col-md-6">
                <strong>Status:</strong>
                <p class="mb-0">
                    <span class="badge bg-{{ $sale->status=='open' ? 'secondary' : ($sale->status=='paid'
                     ? 'success' : ($sale->status=='refunded' ? 'warning' : ($sale->status=='cancelled' 
                     ? 'danger' : 'secondary')))}}">
                        {{ ucfirst($sale->status) }}
                    </span>
                </p>
            </div>

        </div>

        <div class="row mb-3">

            <div class="col-md-6">
                <strong>Total Amount:</strong>
                <p class="mb-0">
                    {{ number_format($sale->total_amount, 2) }}
                </p>
            </div>

            <div class="col-md-6">
                <strong>Paid Amount:</strong>
                <p class="mb-0">
                    {{ number_format($sale->paid_amount, 2) }}
                </p>
            </div>

        </div>

        <div class="row mb-3">

            <div class="col-md-6">
                <strong>Created By:</strong>
                <p class="mb-0">{{ $sale->created_by ?? 'N/A' }}</p>
            </div>

            <div class="col-md-6">
                <strong>Created At:</strong>
                <p class="mb-0">
                    {{ $sale->created_at?->format('d M Y H:i') }}
                </p>
            </div>

        </div>

        <hr>

<h5 class="mb-3">Sale Items</h5>

<div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Book ID</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Sale Price</th>
                <th>Line Total</th>
                <th>Discount</th>
                <th>Total After Discount</th>
            </tr>
        </thead>

        <tbody>
        @forelse($sale->items as $index => $item)

            <tr>
                <td>{{ $index + 1 }}</td>

                <td>{{ $item->book->title }}</td>

                <td>{{ $item->quantity }}</td>

                <td>{{ number_format($item->unit_price, 2) }}</td>

                <td>{{ number_format($item->sale_price, 2) }}</td>

                <td>{{ number_format($item->line_total, 2) }}</td>

                <td>
                    {{ $item->discount ?? 0 }}%
                </td>

                <td>
                    {{ number_format($item->total, 2) }}
                </td>
            </tr>

        @empty

            <tr>
                <td colspan="8" class="text-center">
                    No items added for this sale.
                </td>
            </tr>

        @endforelse
        </tbody>
    </table>
</div>
<hr>

<h5 class="mb-3">Payment Records</h5>

<div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Amount Received</th>
                <th>Payment Type</th>
                <th>Account / Cheque No</th>
                <th>Remarks</th>
                <th>Created By</th>
                <th>Created At</th>
            </tr>
        </thead>

        <tbody>

        @forelse($sale->salesPaymentsDetails as $index => $pay)
            <tr>
                <td>{{ $index + 1 }}</td>

                <td>{{ number_format($pay->amount_received, 2) }}</td>

                <td class="text-capitalize">{{ $pay->payment_type }}</td>

                <td>
                    @if($pay->payment_type == 'cheque')
                        Cheque #: {{ $pay->cheque_number }}
                    @elseif($pay->payment_type == 'online')
                        Account #: {{ $pay->account_number }}
                    @else
                        ---
                    @endif
                </td>

                <td>{{ $pay->remarks ?? '---' }}</td>

                <td>{{ $pay->user->name ?? 'N/A' }}</td>

                <td>
                    {{ $pay->created_at->format('d M Y H:i') }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">
                    No payment history found.
                </td>
            </tr>
        @endforelse

        </tbody>
    </table>
</div>



        <div class="row mt-3">
            <div class="col-md-12 text-end">

                <a href="{{ url('/dashboard/sales/'.$sale->id.'/edit') }}"
                   class="btn btn-warning ">
                   Edit
                </a>

                <form action="{{ url('/dashboard/sales/'.$sale->id) }}"
                    method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="btn btn-danger"
                            onclick="return confirm('Delete this sale?')">
                        Delete
                    </button>
                </form>

                <a href="{{ url('/dashboard/sales') }}"
                   class="btn btn-secondary ">
                    Back
                </a>

            </div>
        </div>
    </div>
</div>


@endsection