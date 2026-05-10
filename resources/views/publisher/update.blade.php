@extends('ui.layouts.simple.master')
@section('title', 'Bootstrap Border Table')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>Update Publisher</h3>


@endsection


@section('content')
    <div class="card">

        <div class="card-body">
            <form class="needs-validation" method="POST" action="{{route('publisher.update',$publisher->id)}}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="validationCustom01">Name</label>
                        <input class="form-control"  type="text" value="{{$publisher->name}}" name="name" placeholder="Name" required="">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="validationCustom02">Contact</label>
                        <input class="form-control" type="text" name="contact" value="{{$publisher->contact}}" placeholder="Contact" >
                    </div><div class="col-md-12 mb-3">
                        <label for="validationCustom02">Address</label>
                        <textarea class="form-control" name="address" id="" placeholder="Address" cols="30" rows="05"  >{{$publisher->address}}</textarea>
                    </div>
                </div>


                <button class="btn btn-primary" type="submit" title="">Update</button>
            </form>
        </div>
    </div>
@endsection
