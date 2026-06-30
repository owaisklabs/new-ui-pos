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
    <form class="needs-validation" action="{{ route('purchase.store') }}" METHOD="post" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="sku">Invoice # <span class="text-danger">*</span></label>
                        <input class="form-control abc" id="sku" type="text" name="invoice_no" placeholder="SKU"
                            required="" data-bs-original-title="" title="">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="purchase_date">Purchase date <span class="text-danger">*</span></label>
                        <input class="form-control" id="purchase_date" type="date" value="{{ now()->format('Y-m-d') }}"
                            name="purchase_date" placeholder="Barcode" required="" data-bs-original-title=""
                            title="">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="supplier_id">Supplier <span class="text-danger">*</span></label>
                        <select id="supplier_id" required class="form-control" name="supplier_id">
                            <option value="">Select Supplier</option>
                            @foreach ($suppliers as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                            <option value="create_new">+ Create New Supplier</option>


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
                        <input class="form-control" id="expense" type="text" name="expense"
                            placeholder="Other Expenses" required="" data-bs-original-title="" title="">
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
                            @foreach ($books as $item)
                                <option value="{{ $item->id }}">{{ $item->title }}</option>
                            @endforeach


                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="quantity_0">Qty <span class="text-danger">*</span></label>
                        <input class="form-control" id="quantity_0" type="number" name="items[0][quantity]"
                            placeholder="Qty" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="unit_cost_0">Unit Cost <span class="text-danger">*</span></label>
                        <input class="form-control" id="unit_cost_0" type="number" name="items[0][unit_cost]"
                            placeholder="Unit Cost" required>
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

        <button class="btn btn-primary  float-end mt-2" type="submit" data-bs-original-title=""
            title="">Create</button>
    </form>

    <div class="modal fade" id="createSupplierModal" tabindex="-1" aria-labelledby="createSupplierModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createSupplierModalLabel">Create New Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createSupplierForm">
                        @csrf
                        <div class="mb-3">
                            <label for="supplier_name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input class="form-control" id="supplier_name" type="text" name="name" placeholder="Name"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="supplier_contact_person" class="form-label">Contact Person</label>
                            <input class="form-control" id="supplier_contact_person" type="text" name="contact_person"
                                placeholder="Contact Person">
                        </div>
                        <div class="mb-3">
                            <label for="supplier_phone" class="form-label">Phone</label>
                            <input class="form-control" id="supplier_phone" type="text" name="phone"
                                placeholder="Phone">
                        </div>
                        <div class="mb-3">
                            <label for="supplier_email" class="form-label">Email</label>
                            <input class="form-control" id="supplier_email" type="email" name="email"
                                placeholder="Email">
                        </div>
                        <div class="mb-3">
                            <label for="supplier_address" class="form-label">Address</label>
                            <input class="form-control" id="supplier_address" type="text" name="address"
                                placeholder="Address">
                        </div>
                        <div id="supplier-form-errors" class="text-danger"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveSupplierBtn">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>

</style>


@section('script')
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
    <script>
        // $(document).ready(function() {
        //     $('.select2').select2({
        //         placeholder: "Select an option",
        //         allowClear: true
        //     });
        // });
        $(document).ready(function() {
            let rowCount = 0;
            const supplierModalEl = document.getElementById('createSupplierModal');
            const supplierModal = new bootstrap.Modal(supplierModalEl);
            $('#add-row').on('click', function() {
                rowCount++;

                const newRow = $(`
                    <div class="row-container">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="book_id_${rowCount}">Book # <span class="text-danger">*</span></label>
                                <select id="book_id_${rowCount}" required class="form-control book-select" name="items[${rowCount}][book_id]">
                                    <option value="">Select Book</option>
                                   @foreach ($books as $item)

                                    <option value="{{ $item->id }}">{{ $item->title }}</option>
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
            $('#supplier_id').on('change', function() {
                if ($(this).val() === 'create_new') {
                    $(this).val('');
                    $('#supplier-form-errors').empty();
                    supplierModal.show();
                }
            });

            $('#saveSupplierBtn').on('click', function() {
                const form = $('#createSupplierForm');
                const saveBtn = $(this);

                saveBtn.prop('disabled', true);

                $.ajax({
                    url: "{{ route('supplier.store') }}",
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        const supplier = response.data;
                        $('#supplier_id option[value="create_new"]').before(
                            `<option value="${supplier.id}">${supplier.name}</option>`
                        );
                        $('#supplier_id').val(supplier.id);
                        form[0].reset();
                        $('#supplier-form-errors').empty();
                        supplierModal.hide();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            let html = '<ul class="mb-0">';
                            Object.values(errors).forEach(function(messages) {
                                messages.forEach(function(message) {
                                    html += `<li>${message}</li>`;
                                });
                            });
                            html += '</ul>';
                            $('#supplier-form-errors').html(html);
                        } else {
                            $('#supplier-form-errors').text('Something went wrong. Please try again.');
                        }
                    },
                    complete: function() {
                        saveBtn.prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endsection
