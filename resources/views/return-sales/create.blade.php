@extends('ui.layouts.simple.master')
@section('title', 'Return Sale')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Return Sale — Invoice #{{ $sale->invoice_no }}</h5>
            <a href="{{ route('sales.show', $sale) }}" class="btn btn-secondary btn-sm">Back to Sale</a>
        </div>

        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="row mb-4">
                <div class="col-md-4">
                    <strong>Customer:</strong>
                    <p class="mb-0">{{ $sale->customer->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4">
                    <strong>Sale Date:</strong>
                    <p class="mb-0">{{ $sale->sale_date }}</p>
                </div>
                <div class="col-md-4">
                    <strong>Total Amount:</strong>
                    <p class="mb-0">{{ number_format($sale->total_amount, 2) }}</p>
                </div>
            </div>

            <form id="returnSaleForm">
                @csrf
                <input type="hidden" name="sale_id" value="{{ $sale->id }}">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Book</th>
                                <th>Sold Qty</th>
                                <th>Already Returned</th>
                                <th>Available</th>
                                <th>Unit Price</th>
                                <th>Return Qty</th>
                                <th>Line Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($sale->items as $index => $item)
                            @php
                                $returned = $item->returnedQuantity();
                                $available = $item->returnableQuantity();
                            @endphp
                            <tr data-sale-item-id="{{ $item->id }}" data-unit-price="{{ $item->sale_price }}">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->book->title ?? 'N/A' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $returned }}</td>
                                <td>{{ $available }}</td>
                                <td>{{ number_format($item->sale_price, 2) }}</td>
                                <td style="width: 120px;">
                                    @if($available > 0)
                                        <input type="number"
                                               class="form-control return-qty"
                                               name="items[{{ $index }}][quantity]"
                                               min="0"
                                               max="{{ $available }}"
                                               value="0"
                                               data-index="{{ $index }}">
                                        <input type="hidden"
                                               name="items[{{ $index }}][sale_item_id]"
                                               value="{{ $item->id }}">
                                    @else
                                        <span class="text-muted">Fully returned</span>
                                    @endif
                                </td>
                                <td class="line-total">0.00</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="7" class="text-end">Total Return Amount</th>
                                <th id="grandTotal">0.00</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Notes (optional)</label>
                    <textarea id="notes" name="notes" class="form-control" rows="3" placeholder="Reason for return..."></textarea>
                </div>

                <div class="text-end">
                    <a href="{{ route('sales.show', $sale) }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-warning" id="submitReturn">Process Return</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    var BASE_URL = "{{ url('') }}";

    function updateLineTotals() {
        let grandTotal = 0;

        $('tbody tr[data-sale-item-id]').each(function () {
            const row = $(this);
            const qty = parseInt(row.find('.return-qty').val(), 10) || 0;
            const unitPrice = parseFloat(row.data('unit-price')) || 0;
            const lineTotal = qty * unitPrice;

            row.find('.line-total').text(lineTotal.toFixed(2));
            grandTotal += lineTotal;
        });

        $('#grandTotal').text(grandTotal.toFixed(2));
    }

    $(document).on('input', '.return-qty', updateLineTotals);

    $('#returnSaleForm').on('submit', function (e) {
        e.preventDefault();

        const items = [];
        $('tbody tr[data-sale-item-id]').each(function () {
            const row = $(this);
            const qtyInput = row.find('.return-qty');
            if (!qtyInput.length) return;

            items.push({
                sale_item_id: row.data('sale-item-id'),
                quantity: parseInt(qtyInput.val(), 10) || 0
            });
        });

        const hasQty = items.some(item => item.quantity > 0);
        if (!hasQty) {
            Swal.fire('Warning', 'Please enter return quantity for at least one item.', 'warning');
            return;
        }

        Swal.fire({
            title: 'Confirm Return',
            text: 'Process this sale return?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, process return'
        }).then(result => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: BASE_URL + '/dashboard/return-sales',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                data: {
                    sale_id: {{ $sale->id }},
                    notes: $('#notes').val(),
                    items: items
                },
                success: function (response) {
                    Swal.fire('Success', response.message, 'success').then(() => {
                        window.location.href = response.redirect_url;
                    });
                },
                error: function (xhr) {
                    const message = xhr.responseJSON?.message || 'Something went wrong.';
                    Swal.fire('Error', message, 'error');
                }
            });
        });
    });
</script>
@endsection
