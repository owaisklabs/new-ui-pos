@extends('ui.layouts.simple.master')
@section('title', 'Bootstrap Border Table')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>Update Supplier</h3>


@endsection


@section('content')
    <div class="card">

        <div class="card-body">
            <form class="needs-validation" method="POST" action="{{route('supplier.update',$supplier->id)}}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="validationCustom01">Name</label>
                        <input class="form-control"  value="{{$supplier->name}}" type="text" name="name" placeholder="Name" required="">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="validationCustom02">Contact Person</label>
                        <input class="form-control" type="text" value="{{$supplier->contact_person}}" name="contact_person" placeholder="Contact" >
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="validationCustom02">Phone</label>
                        <input class="form-control" type="text" value="{{$supplier->phone}}" name="phone" placeholder="Contact" >
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="validationCustom02">Email</label>
                        <input class="form-control" type="email" value="{{$supplier->email}} "name="email" placeholder="Contact" >
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="validationCustom02">Address</label>
                        <input class="form-control" type="text" value="{{$supplier->address}} "name="address" placeholder="Contact" >
                    </div>

                </div>


                <button class="btn btn-primary" type="submit" title="">Update</button>
            </form>
        </div>
    </div>
@endsection
