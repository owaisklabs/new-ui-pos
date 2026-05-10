@extends('ui.layouts.simple.master')
@section('title', 'Bootstrap Border Table')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>Purchases</h3>
@endsection

{{--@section('breadcrumb-items')--}}
{{--    <li class="breadcrumb-item">Authors</li>--}}
{{--@endsection--}}


@section('content')
    <div class="container-fluid">
        {{-- <div class="row"> --}}
        <form action="{{route('purchase.index')}}" class="row" method="GET">
            <div class="col-md-3 mb-3">
                <label for="validationCustom01">Supplier</label>
                <select name="query[book_id]" class="form-control">
                    <option value="">Select Book</option>
                    @foreach($books as $item)
                        <option value="{{$item->id}}">{{$item->title}}</option>
                    @endforeach
                </select>
                <div class="valid-feedback">Looks good!</div>
            </div>
            <div class="col-md-3 mb-3">
                <label for="validationCustom02">From Date</label>
                <input class="form-control" name="from_date" id="validationCustom02" type="date"  >
            </div>
            <div class="col-md-3 mb-3">
                <label for="validationCustom02">To Date</label>
                <input class="form-control" name="to_date" id="validationCustom02" type="date"  >
            </div>
            <div class="col-md-3 mb-3" style="margin-top: 25px;">
                <button class="btn btn-pill btn-primary btn-air-primary btn-lg" type="submit">Search</button>
            </div>
        </form>
        <div class="row">



                <div class="table-responsive">
                    <table class="table table-border-vertical " style="background-color: white;">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Invoice #</th>
                            <th scope="col">Supplier</th>
                            <th scope="col">Date</th>
                            <th scope="col">Purchase Amount</th>
                            <th scope="col">Expense</th>
                            <th scope="col">Total Amount</th>
                            <th scope="col">Items</th>
                            <th scope="col">Status</th>

                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($purchases as $item)

                            <tr>
                                <td scope="row">{{$item->id}}</td>
                                <th >{{$item->invoice_no}}</th>
                                <td>{{$item->supplier->name}}</td>
                                <td>{{$item->purchase_date}}</td>
                                <td>{{number_format($item->total_amount, 2)}}</td>
                                <td>{{number_format($item->expense, 2)}}</td>
                                <td>{{number_format($item->total_amount + $item->expense, 2)}}</td>
                                <td>
                                    @foreach($item->items as $book)
                                        {{$book->book->title ?? '-'}},
                                    @endforeach
                                </td>
                                <td>
                                    <span class="badge bg-{{
                                        $item->status == 'pending' ? 'secondary' :
                                        ($item->status == 'received' ? 'success' :
                                        ($item->status == 'cancelled' ? 'warning' : 'dark'))
                                    }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>

                                @php
                                    $balance= $item->total_amount - $item->paid_amount;
                                @endphp
                                <td class="d-flex align-items-center gap-2">
                                    <a href="{{ route('purchase.show', $item->id) }}" class="btn btn-sm btn-primary p-1 d-flex align-items-center justify-content-center">
                                        <i data-feather="eye"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('purchase.destroy', $item->id) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this purchase?');" class="m-0 p-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger p-1 d-flex align-items-center justify-content-center">
                                            <i data-feather="trash-2"></i>
                                        </button>
                                    </form>

                                    {{-- Edit --}}

                                    <a href="{{ route('purchase.edit', $item->id) }}" class="btn btn-sm btn-primary p-1 d-flex align-items-center justify-content-center">
                                        <i data-feather="edit"></i>
                                    </a>
                                    <button  class="btn btn-sm btn-success p-1 payment-received d-none" data-sales-id="{{$item->id}}" data-receive-amount="{{$balance}}">
                                            <i data-feather="dollar-sign"></i>
                                        </button>

                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <th colspan="4">Total</td>
                            <th >{{number_format($purchases->sum('total_amount'), 2)}}</td>
                            <th >{{number_format($purchases->sum('expense'), 2)}}</td>
                            <th >{{number_format($purchases->sum('total_amount') + $purchases->sum('expense'), 2)}}</td>
                        </tr>
                        </tbody>

                    </table>
                </div>

        </div>
    </div>
@endsection

@section('script')
<script>
    $(".payment-received").click(function () {
            var saleId = $(this).data("sales-id");
            const receiveAmount = $(this).data("receive-amount");
            console.log(saleId,receiveAmount)
            Swal.fire({
                title: "Purchase Amount",
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
                        }
                        else if (this.value === "cheque") {
                            wrapper.style.display = "block";
                            label.innerText = "cheque #";
                        }
                        else {
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
