@extends('ui.layouts.simple.master')

@section('title', 'POS System')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>Create Sale</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('sales.index') }}">Sale</a></li>
    <li class="breadcrumb-item active">Sales Create</li>
@endsection
@section('content')

    <div class="row" id="pos-app">

        <!-- LEFT SIDE -->
        <div class="col-md-6 col-lg-5">

            <!-- Barcode + Customer -->
            <div class="row mb-2">

                <div class="col">
                    <form id="barcode-form">
                        <input
                            type="text"
                            id="barcode"
                            class="form-control"
                            placeholder="Scan Barcode"
                        >
                    </form>
                </div>

                <div class="col">
                    <select id="customer_id" class="form-control">
                        <option value="">General Customer</option>
                    </select>
                </div>

                <div class="col">
                    <select id="status" class="form-control">
                        <option value="open">Open</option>
                        <option value="paid">Paid</option>
                    </select>
                </div>

            </div>

            <!-- CART -->
            <div class="card">

                <table class="table table-striped" id="cart-table">

                    <thead>
                    <tr>
                        <th>Items</th>
                        <th>Items Price</th>
                        <th width="170">Quantity</th>
                        <th width="120">Discount</th>
                        <th>Line Total</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tbody></tbody>

                </table>

            </div>

            <!-- TOTAL -->
            <div class="row mt-2">

                <div class="col-9">
                    <strong>Total:</strong>
                </div>

                <div class="col text-right" id="cart-total">
                    0.00
                </div>

            </div>

            <!-- Buttons -->
            <div class="row mt-3">

                <div class="col">
                    <button id="btn-empty" class="btn btn-danger btn-block">
                        Cancel
                    </button>
                </div>

                <div class="col">
                    <button id="btn-checkout" class="btn btn-primary btn-block">
                        Checkout
                    </button>
                </div>

            </div>

        </div>

        <!-- RIGHT SIDE -->
        <div class="col-md-6 col-lg-7">

            <input
                id="search"
                class="form-control mb-3"
                placeholder="Search Product..."
            >

            <div
                id="product-list"
                class="order-product row text-center"
            ></div>

        </div>

    </div>

@endsection

@section('script')

    <script>

        var BASE_URL = "{{ url('') }}";

        // -----------------------------
        // VALIDATE NUMBER INPUT
        // -----------------------------
        function validateNumber(input) {

            const regex = /^-?\d*\.?\d*$/;

            if (!regex.test(input.value)) {
                input.value = input.value.slice(0, -1);
            }
        }

        // -----------------------------
        // DOCUMENT READY
        // -----------------------------
        $(document).ready(function () {

            loadCustomers();
            loadProducts();
            loadCart();

            // -----------------------------
            // LOAD CUSTOMERS
            // -----------------------------
            function loadCustomers() {

                $.get(
                    BASE_URL + "/dashboard/get-all-customer",
                    function (customers) {

                        customers.data.forEach(c => {

                            $("#customer_id").append(`
                            <option value="${c.id}">
                                ${c.name}
                            </option>
                        `);
                        });
                    }
                );
            }

            // -----------------------------
            // LOAD PRODUCTS
            // -----------------------------
            function loadProducts(search = "") {

                $.get(
                    `${BASE_URL}/dashboard/get-book-by-title?search=${search}`,
                    function (res) {

                        const products = res.data || [];

                        const list = $("#product-list");

                        list.html("");

                        products.forEach(p => {

                            list.append(`
                            <div class="col-6 col-md-3 col-xl-2 mb-4">

                                <div
                                    class="item"
                                    data-barcode="${p.id}"
                                    style="cursor:pointer;"
                                >

                                    <img
                                        src="{{ asset('storage') }}/${p.cover_image}"
                                        class="img-fluid"
                                        style="height:150px;object-fit:cover;"
                                    />

                                    <h6 class="mt-2">
                                        ${p.title}
                                    </h6>

                                </div>

                            </div>
                        `);
                        });
                    }
                );
            }

            // -----------------------------
            // CLICK PRODUCT
            // -----------------------------
            $("#product-list").on("click", ".item", function () {

                const bookId = $(this).data("barcode");

                addProductToCart(bookId);
            });

            // -----------------------------
            // LOAD CART
            // -----------------------------
            function loadCart() {

                $.get(
                    BASE_URL + "/dashboard/cart",
                    function (cart) {

                        renderCart(cart.data);
                    }
                );
            }

            // -----------------------------
            // RENDER CART
            // -----------------------------
            function renderCart(cart) {

                const tbody = $("#cart-table tbody");

                tbody.html("");

                let total = 0;

                cart.forEach(item => {

                    const quantity = item.pivot.quantity;
                    const factor = parseFloat(item.conversion_factor) || 0;
                    const isDual = factor > 1;
                    const packUnit = item.purchase_unit || 'Roll';
                    const baseUnit = item.base_unit || 'Feet';
                    const rollQty = parseInt(item.pivot.roll_qty, 10) || 0;
                    let feetQty = parseInt(item.pivot.feet_qty, 10) || 0;
                    if (rollQty === 0 && feetQty === 0) {
                        feetQty = quantity;
                    }

                    const qtyCell = isDual ? `
                        <div class="d-flex gap-1">
                            <div>
                                <small>${packUnit}s</small>
                                <input type="text" oninput="validateNumber(this)"
                                    class="form-control form-control-sm roll-input"
                                    name="roll_qty[]" value="${rollQty}"
                                    data-id="${item.id}" data-factor="${factor}" data-base="${baseUnit}">
                            </div>
                            <div>
                                <small>${baseUnit}</small>
                                <input type="text" oninput="validateNumber(this)"
                                    class="form-control form-control-sm feet-input"
                                    name="feet_qty[]" value="${feetQty}"
                                    data-id="${item.id}">
                            </div>
                        </div>
                        <small class="text-info equiv">= ${quantity} ${baseUnit}</small>
                        <input type="hidden" class="qty-input" name="qty[]" value="${quantity}" data-id="${item.id}">
                    ` : `
                        <input
                            type="text"
                            oninput="validateNumber(this)"
                            class="form-control form-control-sm qty-input"
                            name="qty[]"
                            value="${quantity}"
                            data-id="${item.id}"
                        >
                    `;

                    const discount = item.pivot.discount || 0;

                    const lineTotal =
                        quantity *
                        item.sell_price *
                        (1 - discount / 100);

                    total += lineTotal;

                    tbody.append(`

                    <tr>

                        <td>${item.title}</td>

                        <td class="text-right">
                            ${item.sell_price}${isDual ? ' / ' + baseUnit : ''}
                            ${isDual ? '<br><small class="text-muted">' + (item.sell_price * factor).toFixed(2) + ' / ' + packUnit + '</small>' : ''}
                        </td>

                        <td>
                            ${qtyCell}
                        </td>

                        <input
                            type="hidden"
                            name="book_id[]"
                            value="${item.id}"
                        >

                        <input
                            type="hidden"
                            name="book_price[]"
                            value="${item.sell_price}"
                        >

                        <input
                            type="hidden"
                            name="cost_price[]"
                            value="${item.cost_price}"
                        >

                        <input
                            type="hidden"
                            name="line_total[]"
                            value="${lineTotal.toFixed(2)}"
                        >

                        <td>

                            <input
                                type="text"
                                oninput="validateNumber(this)"
                                name="discount[]"
                                class="form-control form-control-sm discount-input"
                                value="${discount}"
                                data-id="${item.id}"
                            >

                        </td>

                        <td class="text-right line-total">
                            ${lineTotal.toFixed(2)}
                        </td>

                        <td>

                            <button
                                class="btn btn-danger btn-sm btn-del"
                                data-id="${item.id}"
                                style="padding:4px;"
                            >

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="15"
                                     height="15"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round">

                                    <line x1="18" y1="6" x2="6" y2="18"/>
                                    <line x1="6" y1="6" x2="18" y2="18"/>

                                </svg>

                            </button>

                        </td>

                    </tr>

                `);
                });

                $("#cart-total").text(total.toFixed(2));
            }

            // -----------------------------
            // BARCODE SUBMIT
            // -----------------------------
            $("#barcode-form").submit(function (e) {

                e.preventDefault();

                const barcode = $("#barcode").val();

                if (!barcode) return;

                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN":
                            $('meta[name="csrf-token"]').attr("content")
                    }
                });

                $.get(
                    BASE_URL + "/dashboard/get-book-by-barcode",
                    { barcode: barcode },
                    function (res) {
                        const book = res.data;
                        if(book){
                            addProductToCart(book.id);
                        }
                        $("#barcode").val("");
                    }
                ).fail(err => {

                    Swal.fire(
                        "Error!",
                        err.responseJSON.message,
                        "error"
                    );
                });
            });

            // -----------------------------
            // ADD PRODUCT TO CART
            // -----------------------------
            function addProductToCart(bookId) {

                $('.loader-wrapper').fadeIn();

                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN":
                            $('meta[name="csrf-token"]').attr("content")
                    }
                });

                $.post(
                    BASE_URL + "/dashboard/cart",
                    { bookId },
                    function () {

                        loadCart();

                        $('.loader-wrapper').fadeOut();
                    }
                ).fail(err => {

                    Swal.fire(
                        "Error!",
                        err.responseJSON.message,
                        "error"
                    );

                    $('.loader-wrapper').fadeOut();
                });
            }

            // -----------------------------
            // DEBOUNCE
            // -----------------------------
            function debounce(func, delay) {

                let timer;

                return function (...args) {

                    clearTimeout(timer);

                    timer = setTimeout(() => {
                        func.apply(this, args);
                    }, delay);
                };
            }

            // -----------------------------
            // UPDATE ROW TOTAL
            // -----------------------------
            function updateRowTotal(row) {

                const rollInput = row.find(".roll-input");

                if (rollInput.length) {
                    const factor = parseFloat(rollInput.data("factor")) || 0;
                    const rolls = parseInt(rollInput.val(), 10) || 0;
                    const feet = parseInt(row.find(".feet-input").val(), 10) || 0;
                    row.find(".qty-input").val(rolls * factor + feet);
                    row.find(".equiv").text("= " + (rolls * factor + feet) + " " + rollInput.data("base"));
                }

                const qty =
                    parseFloat(row.find(".qty-input").val()) || 0;

                const discount =
                    parseFloat(row.find(".discount-input").val()) || 0;

                const price =
                    parseFloat(
                        row.find('input[name="book_price[]"]').val()
                    ) || 0;

                const lineTotal =
                    qty * price * (1 - discount / 100);

                row.find(".line-total").text(
                    lineTotal.toFixed(2)
                );

                row.find('input[name="line_total[]"]').val(
                    lineTotal.toFixed(2)
                );

                updateCartTotal();
            }

            // -----------------------------
            // UPDATE GRAND TOTAL
            // -----------------------------
            function updateCartTotal() {

                let total = 0;

                $("#cart-table tbody tr").each(function () {

                    total += parseFloat(
                        $(this)
                            .find('input[name="line_total[]"]')
                            .val()
                    ) || 0;
                });

                $("#cart-total").text(total.toFixed(2));
            }

            // -----------------------------
            // SERVER SYNC
            // -----------------------------
            const syncCart = debounce(function (row) {

                const id =
                    row.find(".qty-input").data("id");

                const qty =
                    parseFloat(row.find(".qty-input").val()) || 0;

                const discount =
                    parseFloat(
                        row.find(".discount-input").val()
                    ) || 0;

                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN":
                            $('meta[name="csrf-token"]').attr("content")
                    }
                });

                $.post(
                    BASE_URL + "/dashboard/cart/change-qty",
                    {
                        product_id: id,
                        quantity: qty,
                        roll_qty: parseInt(row.find(".roll-input").val(), 10) || 0,
                        feet_qty: parseInt(row.find(".feet-input").val(), 10) || 0,
                        discount: discount
                    }
                ).fail(err => {

                    Swal.fire(
                        "Error!",
                        err.responseJSON.message,
                        "error"
                    );
                });

            }, 500);

            // -----------------------------
            // INPUT EVENT
            // -----------------------------
            $("#cart-table").on(
                "input",
                ".qty-input, .discount-input, .roll-input, .feet-input",
                function () {

                    const row = $(this).closest("tr");

                    updateRowTotal(row);

                    syncCart(row);
                }
            );

            // -----------------------------
            // DELETE ITEM
            // -----------------------------
            $("#cart-table").on("click", ".btn-del", function () {

                const id = $(this).data("id");

                $('.loader-wrapper').fadeIn();

                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN":
                            $('meta[name="csrf-token"]').attr("content")
                    }
                });

                $.post(
                    BASE_URL + "/dashboard/cart/delete",
                    {
                        product_id: id,
                        _method: "DELETE"
                    },
                    function () {

                        loadCart();

                        $('.loader-wrapper').fadeOut();
                    }
                );
            });

            // -----------------------------
            // EMPTY CART
            // -----------------------------
            $("#btn-empty").click(function () {

                $('.loader-wrapper').fadeIn();

                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN":
                            $('meta[name="csrf-token"]').attr("content")
                    }
                });

                $.post(
                    BASE_URL + "/dashboard/cart/empty",
                    { _method: "DELETE" },
                    function () {

                        loadCart();

                        $('.loader-wrapper').fadeOut();
                    }
                );
            });

            // -----------------------------
            // SEARCH PRODUCTS
            // -----------------------------
            $("#search").on(
                "input",
                debounce(function () {

                    loadProducts($(this).val());

                }, 400)
            );

            // -----------------------------
            // CHECKOUT
            // -----------------------------
            $("#btn-checkout").click(function () {

                let cartData = [];

                $('#cart-table tbody tr').each(function () {

                    cartData.push({

                        roll_qty:
                            $(this).find('.roll-input').val(),

                        feet_qty:
                            $(this).find('.feet-input').val(),

                        qty:
                            $(this)
                                .find('input[name="qty[]"]')
                                .val(),

                        discount:
                            $(this)
                                .find('input[name="discount[]"]')
                                .val(),

                        price:
                            $(this)
                                .find('input[name="book_price[]"]')
                                .val(),

                        cost_price:
                            $(this)
                                .find('input[name="cost_price[]"]')
                                .val(),

                        line_total:
                            $(this)
                                .find('input[name="line_total[]"]')
                                .val(),

                        book_id:
                            $(this)
                                .find('input[name="book_id[]"]')
                                .val(),
                    });
                });

                const total = $("#cart-total").text();

                Swal.fire({

                    title: "Received Amount",

                    input: "text",

                    inputValue: total,

                    showCancelButton: true,

                    confirmButtonText: "Confirm",

                }).then(result => {

                    if (!result.value) return;

                    $.ajaxSetup({
                        headers: {
                            "X-CSRF-TOKEN":
                                $('meta[name="csrf-token"]').attr("content")
                        }
                    });

                    $.post(
                        BASE_URL + "/dashboard/sales",
                        {
                            customer_id:
                                $("#customer_id").val(),

                            sales_status:
                                $("#status").val(),

                            amount:
                            result.value,

                            cartData:
                            cartData
                        },
                        function (res) {

                            window.location.href =
                                `${BASE_URL}/dashboard/print-receipt/${res.data.id}`;

                            loadCart();
                        }
                    ).fail(err => {

                        Swal.fire(
                            "Error!",
                            err.responseJSON.message,
                            "error"
                        );
                    });
                });
            });

        });

    </script>

@endsection
