@extends('ui.layouts.simple.master')
@section('title', 'Bootstrap Border Table')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>update Author</h3>


@endsection


@section('content')
    <div class="card">

        <div class="card-body">
            <form class="needs-validation" novalidate="" action="{{route('customer.update',$customer->id)}}" METHOD="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="validationCustom01">Name</label>
                        <input class="form-control" id="validationCustom01" type="text" value="{{$customer->name}}" name="name" placeholder="Name" required="" data-bs-original-title="" title="">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="validationCustom02">Phone</label>
                        <input class="form-control" id="validationCustom02" type="text" value="{{$customer->phone}}" name="phone" placeholder="Bio" required="" data-bs-original-title="" title="">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="validationCustom02">Address</label>
                        <input class="form-control" id="validationCustom02" type="text" value="{{$customer->address}}" name="address" placeholder="Bio" required="" data-bs-original-title="" title="">
                    </div>

                </div>


                <button class="btn btn-primary" type="submit" data-bs-original-title="" title="">Update</button>
            </form>
        </div>
    </div>
@endsection
