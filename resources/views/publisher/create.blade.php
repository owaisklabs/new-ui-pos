@extends('ui.layouts.simple.master')
@section('title', 'Bootstrap Border Table')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>Create Publisher</h3>


@endsection


@section('content')
    <div class="card">

        <div class="card-body">
            <form class="needs-validation" method="POST" action="{{route('publisher.store')}}">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="validationCustom01">Name</label>
                            <input class="form-control"  type="text" name="name" placeholder="Name" required="">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="validationCustom02">Contact</label>
                        <input class="form-control" type="text" name="contact" placeholder="Contact" >
                    </div><div class="col-md-12 mb-3">
                        <label for="validationCustom02">Address</label>
                        <textarea class="form-control" name="address" id="" placeholder="Address" cols="30" rows="05"  ></textarea>
                    </div>
                </div>


                <button class="btn btn-primary" type="submit" title="">Create</button>
            </form>
        </div>
    </div>
@endsection
