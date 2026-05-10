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
    <form class="needs-validation" action="{{route('purchase.update',$purchase->id)}}" METHOD="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label for="sku">Invoice # <span class="text-danger">*</span></label>
                        <input class="form-control abc" id="sku" type="text" value="{{$purchase->invoice_no}}" name="invoice_no" placeholder="SKU" required=""
                               data-bs-original-title="" title="">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="purchase_date">Purchase date <span class="text-danger">*</span></label>
                        <input class="form-control" id="purchase_date" value="{{$purchase->purchase_date}}" type="date" value="{{ now()->format('Y-m-d') }}"
                               name="purchase_date" placeholder="Barcode" required="" data-bs-original-title="" title="">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="publisher_id">Supplier <span class="text-danger">*</span></label>
                        <select id=" publisher_id" required class="form-control " name="supplier_id">
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $item)

                                <option value="{{$item->id}}"  {{$item->id == $purchase->supplier_id ? "selected" : "" }}>{{$item->name}}</option>
                            @endforeach

                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="payment_type">Payment type <span class="text-danger">*</span></label>
                        <select id="payment_type" required class="form-control " name="payment_type">
                            <option value="" >Select Payment type</option>
                            <option value="cash">Cash</option>
                            <option value="online">Online</option>
                            <option value="cheque">Cheque</option>


                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="status">Status <span class="text-danger">*</span></label>

                        <select id=" status" required class="form-control " name="status">
                            <option value="">Select Status</option>
                            <option value="received" {{$purchase->status  =='received'  ? "selected" : "" }}>Received</option>
                            <option value="pending" {{$purchase->status  =='pending'  ? "selected" : "" }}>Pending</option>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="remarks">Remarks <span class="text-danger">*</span></label>

                        <textarea class="form-control textarea" name="remarks" rows="3" cols="50" placeholder="Remarks">{{$purchase->remarks}} </textarea>
                    </div>
                </div>
            </div>
        </div>


        <button class="btn btn-primary  float-end mt-2" type="submit" data-bs-original-title="" title="">Update</button>
    </form>
@endsection

<style>

</style>


@section('script')

@endsection



