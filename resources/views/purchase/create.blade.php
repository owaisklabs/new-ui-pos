@extends('ui.layouts.simple.master')
@section('title', 'Bootstrap Border Table')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>Create Purchase</h3>


@endsection


@section('content')
    <form class="needs-validation" action="{{route('purchase.store')}}" METHOD="post" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="sku">Invoice # <span class="text-danger">*</span></label>
                        <input class="form-control abc" id="sku" type="text" name="invoice_no" placeholder="SKU" required=""
                               data-bs-original-title="" title="">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="purchase_date">Purchase date <span class="text-danger">*</span></label>
                        <input class="form-control" id="purchase_date" type="date" value="{{ now()->format('Y-m-d') }}"
                               name="purchase_date" placeholder="Barcode" required="" data-bs-original-title="" title="">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="publisher_id">Supplier <span class="text-danger">*</span></label>
                        <select id=" publisher_id" required class="form-control " name="supplier_id">
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $item)

                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach

                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="payment_type">Payment type <span class="text-danger">*</span></label>
                        <select id="payment_type" required class="form-control " name="payment_type">
                            <option value="">Select Payment type</option>
                            <option value="cash">Cash</option>
                            <option value="online">Online</option>
                            <option value="cheque">Cheque</option>


                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="status">Status <span class="text-danger">*</span></label>

                        <select id=" status" required class="form-control " name="status">
                            <option value="">Select Status</option>
                            <option value="received">Received</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="expense">Other Expenses <span class="text-danger">*</span></label>
                        <input class="form-control" id="expense" type="text"
                               name="expense" placeholder="Other Expenses" required="" data-bs-original-title="" title="">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="remarks">Remarks <span class="text-danger">*</span></label>

                        <textarea class="form-control textarea" name="remarks" rows="3" cols="50" placeholder="Remarks"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <h3>Purchase Items</h3>
        <div id="rows-container">
            <div class="row-container">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="book_id_0">Book # <span class="text-danger">*</span></label>
                        <select id="book_id_0" required class="form-control" name="items[0][book_id]">
                            <option value="">Select Book</option>
                            @foreach($books as $item)
                                <option value="{{$item->id}}">{{$item->title}}</option>
                            @endforeach


                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="quantity_0">Qty <span class="text-danger">*</span></label>
                        <input class="form-control" id="quantity_0" type="number" name="items[0][quantity]" placeholder="Qty" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="unit_cost_0">Unit Cost <span class="text-danger">*</span></label>
                        <input class="form-control" id="unit_cost_0" type="number" name="items[0][unit_cost]" placeholder="Unit Cost" required>
                    </div>
                    <div class="col-md-2 mb-3 action-buttons">
                        <!-- Remove button hidden for the first row -->
                        <button type="button" class="btn btn-danger remove-row mt-4" style="display: none;">
                            -
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <button type="button" class="btn btn btn-outline-dark-2x" id="add-row">
                    +
                </button>
            </div>
        </div>

        <button class="btn btn-primary  float-end mt-2" type="submit" data-bs-original-title="" title="">Create</button>
    </form>
@endsection

<style>

</style>


@section('script')
    <script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
    <script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
    <script>
        // $(document).ready(function() {
        //     $('.select2').select2({
        //         placeholder: "Select an option",
        //         allowClear: true
        //     });
        // });
        $(document).ready(function() {
            let rowCount = 0;
            $('#add-row').on('click', function() {
                rowCount++;

                const newRow = $(`
                    <div class="row-container">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="book_id_${rowCount}">Book # <span class="text-danger">*</span></label>
                                <select id="book_id_${rowCount}" required class="form-control book-select" name="items[${rowCount}][book_id]">
                                    <option value="">Select Book</option>
                                   @foreach($books as $item)

                                    <option value="{{$item->id}}">{{$item->title}}</option>
                                      @endforeach

                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="quantity_${rowCount}">Qty <span class="text-danger">*</span></label>
                                <input class="form-control quantity-input" id="quantity_${rowCount}" type="number" name="items[${rowCount}][quantity]" placeholder="Qty" min="1" value="1" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="unit_cost_${rowCount}">Unit Cost <span class="text-danger">*</span></label>
                                <input class="form-control unit-cost-input" id="unit_cost_${rowCount}" type="number" name="items[${rowCount}][unit_cost]" placeholder="Unit Cost" step="0.01" min="0" required>
                            </div>
                            <div class="col-md-2 mb-3 action-buttons">
                                <button type="button" class="btn btn-danger mt-4 remove-row">
                                    -
                                </button>
                            </div>
                        </div>
                    </div>
                `);

                $('#rows-container').append(newRow);

                // Show remove buttons on all rows if there's more than one
                if (rowCount > 1) {
                    $('.remove-row').show();
                }
                updateTotals();
            });
            $(document).on('click', '.remove-row', function() {
                $(this).closest('.row-container').remove();
                rowCount--;

                // Hide remove buttons if only one row remains
                if (rowCount === 1) {
                    $('.remove-row').hide();
                }
            });

            $('#submit-form').on('click', function() {

            });
        });


    </script>
@endsection



