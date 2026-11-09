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
            <div class="col-md-2 mb-3">
                <label for="validationCustom01">Invoice No</label>
                <select name="query[book_id]" class="form-control">
                    <option value="">Select Book</option>
                    @foreach($books as $item)
                        <option value="{{$item->id}}">{{$item->title}}</option>
                    @endforeach
                </select>
                <div class="valid-feedback">Looks good!</div>
            </div>
            <div class="col-md-2 mb-3">
                <label for="validationCustom01">Supplier</label>
                <select name="query[book_id]" class="form-control">
                    <option value="">Select Book</option>
                    @foreach($books as $item)
                        <option value="{{$item->id}}">{{$item->title}}</option>
                    @endforeach
                </select>
                <div class="valid-feedback">Looks good!</div>
            </div>
            <div class="col-md-2 mb-3">
                <label for="validationCustom02">From Date</label>
                <input class="form-control" name="from_date" id="validationCustom02" type="date"  >
            </div>
            <div class="col-md-2 mb-3">
                <label for="validationCustom02">To Date</label>
                <input class="form-control" name="to_date" id="validationCustom02" type="date"  >
            </div>
            <div class="col-md-2 mb-3" style="margin-top: 25px;">
                <button class="btn btn-pill btn-primary btn-air-primary btn-lg" type="submit">Search</button>
            </div>
            <div class="col-md-2 mb-3" style="margin-top: 25px;">
                <a href="{{route('purchase.create')}}" class="btn btn-success btn-air-success btn-lg text-nowrap" type="submit">Create Purchase</a>
            </div>
        </form>
        <div class="row">
            <div class="col-xl-4 chart_data_right box-col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="media-body right-chart-content">
                                <h4>{{ number_format($totals['total_amount_sum'], 2) }}</h4>
                                <span>Total Purchase Amount</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 chart_data_right box-col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="media-body right-chart-content">
                                <h4>{{ number_format($totals['paid_amount_sum'], 2) }}</h4>
                                <span>Amount Paid</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 chart_data_right box-col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="media-body right-chart-content">
                                <h4>{{ number_format($totals['total_amount_sum'] - $totals['paid_amount_sum'], 2) }}</h4>
                                <span>Amount Remain (Credit)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                            <th scope="col">Paid Amount</th>
                            <th scope="col">Balance</th>
                            <th scope="col">Items</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($purchases as $item)
                            @php
                                $payable = $item->payableAmount();
                                $balance = $item->balanceAmount();
                                $paymentLabel = $item->paymentLabel();
                                $statusClass = [
                                    'Credit' => 'bg-warning text-dark',
                                    'Paid' => 'bg-success',
                                    'Pending' => 'bg-secondary',
                                    'Received' => 'bg-success',
                                    'Cancelled' => 'bg-danger',
                                    'Returned' => 'bg-dark',
                                ][$paymentLabel] ?? 'bg-secondary';
                            @endphp
                            <tr>
                                <td scope="row">{{$item->id}}</td>
                                <th >{{$item->invoice_no}}</th>
                                <td>{{$item->supplier->name ?? '-'}}</td>
                                <td>{{$item->purchase_date}}</td>
                                <td>{{number_format($item->total_amount, 2)}}</td>
                                <td>{{number_format($item->expense, 2)}}</td>
                                <td>{{number_format($payable, 2)}}</td>
                                <td>{{number_format($item->paid_amount, 2)}}</td>
                                <td>{{number_format($balance, 2)}}</td>
                                <td>
                                    @foreach($item->items as $book)
                                        {{$book->book->title ?? '-'}},
                                    @endforeach
                                </td>
                                <td>
                                    <span class="badge {{ $statusClass }}">{{ $paymentLabel }}</span>
                                </td>
                                <td class="d-flex align-items-center gap-2">
                                    <a href="{{ route('purchase.show', $item->id) }}" class="btn btn-sm btn-primary p-1 d-flex align-items-center justify-content-center">
                                        <i data-feather="eye"></i>
                                    </a>

                                    <form action="{{ route('purchase.destroy', $item->id) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this purchase?');" class="m-0 p-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger p-1 d-flex align-items-center justify-content-center">
                                            <i data-feather="trash-2"></i>
                                        </button>
                                    </form>

                                    <a href="{{ route('purchase.edit', $item->id) }}" class="btn btn-sm btn-primary p-1 d-flex align-items-center justify-content-center">
                                        <i data-feather="edit"></i>
                                    </a>
                                    @if($balance > 0 && !in_array($item->status, ['cancelled', 'returned']))
                                        <button type="button" class="btn btn-sm btn-success p-1 payment-received" data-purchase-id="{{$item->id}}" data-receive-amount="{{$balance}}">
                                            <i data-feather="dollar-sign"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <th colspan="4">Total</th>
                            <th>{{ number_format($purchases->sum('total_amount'), 2) }}</th>
                            <th>{{ number_format($purchases->sum('expense'), 2) }}</th>
                            <th>{{ number_format($purchases->sum('total_amount') + $purchases->sum('expense'), 2) }}</th>
                            <th>{{ number_format($purchases->sum('paid_amount'), 2) }}</th>
                            <th>{{ number_format(($purchases->sum('total_amount') + $purchases->sum('expense')) - $purchases->sum('paid_amount'), 2) }}</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </tbody>

                    </table>
                </div>

        </div>
    </div>
@endsection

@section('script')
<script>
    var BASE_URL = "{{ url('') }}";

    $(".payment-received").click(function () {
            var purchaseId = $(this).data("purchase-id");
            const receiveAmount = $(this).data("receive-amount");
            Swal.fire({
                title: "Paid Amount",
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
                    <div id="remarks" style="margin-top:10px;">
                        <label>Remarks</label>
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
                    const amount = parseFloat(document.getElementById("amount").value);
                    if (!amount || amount <= 0) {
                        Swal.showValidationMessage("Enter an amount greater than 0");
                        return false;
                    }
                    if (amount > parseFloat(receiveAmount)) {
                        Swal.showValidationMessage("Amount is greater than the remaining balance");
                        return false;
                    }
                    return {
                        amount: amount,
                        payment_type: document.getElementById("payment_type").value,
                        extra: document.getElementById("extra_value").value || "",
                        extra_remarks: document.getElementById("extra_remarks").value || ""
                    };
                }
            }).then(result => {
                if (!result.value) return;

                $.ajax({
                    url: `${BASE_URL}/dashboard/purchase-payment`,
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    },
                    data: {
                        purchase_id: purchaseId,
                        receive_amount: result.value.amount,
                        payment_type: result.value.payment_type,
                        extra_value: result.value.extra,
                        remark: result.value.extra_remarks
                    },
                    success: function (response) {
                        window.location.href = response.redirect_url;
                    },
                    error: function (err) {
                        Swal.fire("Error!", (err.responseJSON && err.responseJSON.message) || "Payment failed", "error");
                    }
                });
            });
        })
</script>
@endsection
