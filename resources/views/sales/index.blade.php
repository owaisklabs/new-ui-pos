@extends('ui.layouts.simple.master')
@section('title', 'Bootstrap Border Table')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>Sales</h3>
@endsection

{{--@section('breadcrumb-items')--}}
{{--    <li class="breadcrumb-item">Authors</li>--}}
{{--@endsection--}}


@section('content')
    <div class="container-fluid">
        {{-- <div class="row"> --}}
        <form action="#" class="row" method="GET">
            <div class="col-md-2 mb-3">
                <label for="validationCustom01">Invoice</label>
                <input class="form-control" name="query[invoice_no]"
                       value="{{ request()->query('query')['invoice_no'] ?? '' }}" type="text" placeholder="First name">
            </div>
            <input type="hidden" id="selected_customer_id"
                   value="{{ request()->query('query')['customer_id'] ?? '' }}">
            <div class="col-md-2 mb-3">
                <label for="validationCustom01">Customer</label>
                <select id="customer_id" name="query[customer_id]" class="form-control">
                    <option value="">Select Customer</option>
                </select>
            </div>
            <div class="col-md-2 mb-3">
                <label for="validationCustom02">From Date</label>
                <input class="form-control" name="query[from_date]"
                       value="{{ request()->query('query')['from_date'] ?? '' }}" id="validationCustom02" type="date">
            </div>
            <div class="col-md-2 mb-3">
                <label for="validationCustom02">To Date</label>
                <input class="form-control" name="query[to_date]"
                       value="{{ request()->query('query')['to_date'] ?? '' }}" id="validationCustom02" type="date">
            </div>
            <div class="col-md-1 mb-3" style="margin-top: 25px;">
                <button class="btn  btn-primary btn-air-primary btn-lg" type="submit">Search</button>
            </div>
            <div class="col-md-1 mb-3" style="margin-top: 25px;">
                <button class="btn  btn-warning btn-air-warning btn-lg" onclick="clearSearch()">Clear</button>
            </div>
            <div class="col-md-2 mb-3" style="margin-top: 25px;">
                <a href="{{route('sales.create')}}" class="btn  btn-success btn-air-success btn-lg">Create sales</a>
            </div>
        </form>
        {{-- </div> --}}
        <div class="row">
            <div class="col-xl-4  chart_data_right box-col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="media-body right-chart-content">
{{--                                @dd($totals)--}}
                                <h4>{{number_format($totals['total_amount_sum'],2)}}</h4>
                                <span>Total Sales</span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4  chart_data_right box-col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="media-body right-chart-content">
                                <h4>{{number_format($totals['paid_amount_sum'],2)}}</h4>
                                <span>Total Amount Recived</span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4  chart_data_right box-col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="media-body right-chart-content">
                                <h4>{{number_format($totals['total_amount_sum']-$totals['paid_amount_sum'],2)}}</h4>
                                <span>Total Amount Balance</span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-border-vertical " style="background-color: white;">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Invoice #</th>
                            <th scope="col">Customer</th>
                            <th scope="col">Date</th>
                            <th scope="col">Total Amount</th>
                            <th scope="col">Total Item Qty</th>
                            <th scope="col">Paid Amount</th>
                            <th scope="col">Balance</th>
                            <th scope="col">Status</th>

                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($sales as $item)
                            @php
                                $statusClass = [
                                    'open'      => 'bg-warning text-dark',
                                    'paid'      => 'bg-success',
                                    'cancelled' => 'bg-danger',
                                    'refund'    => 'bg-orange text-white', // custom color needed
                                ][$item->status] ?? 'bg-secondary';

                            $balance= $item->total_amount - $item->paid_amount;
                            @endphp

                            <tr>
                                <td scope="row">{{$item->id}}</td>
                                <th>{{$item->invoice_no}}</th>
                                <th>{{$item->customer->name ?? "-"}}</th>
                                <td>{{$item->sale_date}}</td>
                                <td>{{number_format($item->total_amount,2)}}</td>
                                <td>{{$item->total_items_quantity}}</td>
                                <td>{{number_format($item->paid_amount,2)}}</td>
                                <td>{{number_format($balance,2) }}</td>
                                <td><span
                                        class="badge rounded-pill {{ $statusClass }}">{{ ucfirst($item->status) }}</span>
                                </td>
                                <td class="">
                                    <div class="d-flex  gap-1">
                                        {{-- View --}}
                                        <a href="{{ route('sales.show', $item->id) }}"
                                           class="btn btn-sm btn-primary p-1">
                                            <i data-feather="eye"></i>
                                        </a>

                                        {{-- Edit --}}
                                        <a href="{{ route('sales.edit', $item->id) }}"
                                           class="btn btn-sm btn-primary p-1">
                                            <i data-feather="edit"></i>
                                        </a>

                                        {{-- Payment --}}
                                        @if($item->status != 'paid')
                                            <button class="btn btn-sm btn-success p-1 payment-received"
                                                    data-sales-id="{{$item->id}}" data-receive-amount="{{$balance}}">
                                                <i data-feather="dollar-sign"></i>
                                            </button>
                                        @endif

                                        {{-- Delete --}}
                                        <form action="{{ route('sales.destroy', $item->id) }}" method="POST"
                                              class="m-0 p-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger p-1">
                                                <i data-feather="trash-2"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <th colspan="4">Total
                            </td>
                            <th>
                            {{number_format($sales->sum('total_amount'), 2)}}</td>
                            <th>
                            {{number_format($sales->sum('paid_amount'), 2)}}</td>
                            <th>
                            {{number_format($sales->sum('total_amount') - $sales->sum('paid_amount'), 2)}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                {{ $sales->links('pagination::bootstrap-5')}}
            </div>
        </div>
    </div>
@endsection

@section('script')

    <script>
        var BASE_URL = "{{ url('') }}";

        function clearSearch() {
            // Clear text input
            $("input[name='query[invoice_no]']").val("");

            // Clear customer dropdown
            $("#customer_id").val("");

            // Clear dates
            $("input[name='query[from_date]']").val("");
            $("input[name='query[to_date]']").val("");

            // Submit the form after clearing
            $("form").submit();
        }

        loadCustomers();

        function loadCustomers() {
            $.get(BASE_URL + "/dashboard/get-all-customer", function (customers) {

                let selectedCustomer = $("#selected_customer_id").val();

                customers.data.forEach(c => {
                    let selected = (selectedCustomer == c.id) ? 'selected' : '';
                    $("#customer_id").append(
                        `<option value="${c.id}" ${selected}>${c.name}</option>`
                    );
                });
            });
        }

        $(".payment-received").click(function () {
            var saleId = $(this).data("sales-id");
            const receiveAmount = $(this).data("receive-amount");
            console.log(saleId, receiveAmount)
            Swal.fire({
                title: "Received Amount",
                html: `
                    <label>Amount</label>
                    <input id="amount" class="swal2-input" type="text" value="${receiveAmount}">

                    <label>Payment Type</label>
                    <select id="payment_type" class="swal2-select">
                        <option value="cash">Cash</option>
                        <option value="online">Online</option>
                        <option value="cheque">Cheque</option>
                    </select>

                    <div id="extra_field_wrapper" style="display:none; margin-top:10px;">
                        <label id="extra_label"></label>
                        <input id="extra_value" class="swal2-input" type="text">
                    </div>
                    <div id="remarks"  margin-top:10px;">
                        <label id=""> Reamrks</label>
                        <input id="extra_remarks" class="swal2-input" type="text">
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: "Confirm",

                didOpen: () => {
                    const paymentType = document.getElementById("payment_type");
                    const wrapper = document.getElementById("extra_field_wrapper");
                    const label = document.getElementById("extra_label");

                    paymentType.addEventListener("change", function () {
                        if (this.value === "online") {
                            wrapper.style.display = "block";
                            label.innerText = "Acc #";
                        } else if (this.value === "cheque") {
                            wrapper.style.display = "block";
                            label.innerText = "cheque #";
                        } else {
                            wrapper.style.display = "none";
                        }
                    });
                },

                preConfirm: () => {
                    return {
                        amount: document.getElementById("amount").value,
                        payment_type: document.getElementById("payment_type").value,
                        extra: document.getElementById("extra_value").value || "",
                        extra_remarks: document.getElementById("extra_remarks").value || ""
                    };
                }
            }).then(result => {
                if (!result.value) return;

                const amount = result.value.amount;
                const paymentType = result.value.payment_type;
                const extra = result.value.extra;
                const remarks = result.value.extra_remarks;

                console.log("Amount:", amount);
                console.log("sale Id:", saleId);
                console.log("Payment Type:", paymentType);
                console.log("Extra Value:", extra);
                console.log("remarks:", remarks);
                $.get(`${BASE_URL}/dashboard/payment-receive`, {
                    sale_id: saleId,
                    receive_amount: amount,
                    payment_type: paymentType,
                    extra_value: extra,    // account number or cheque number
                    remark: remarks    // account number or cheque number
                }, function (response) {
                    window.location.href = response.redirect_url;
                })
                    .fail(err => {
                        Swal.fire("Error!", err.responseJSON.message, "error");
                    });
            });


        })

    </script>
@endsection
