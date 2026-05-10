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
            <form class="needs-validation" novalidate="" action="{{route('author.update',$author->id)}}" METHOD="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="validationCustom01">Name</label>
                        <input class="form-control" id="validationCustom01" value="{{$author->name}}" type="text" name="name" placeholder="Name" required="" data-bs-original-title="" title="">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="validationCustom02">Bio</label>
                        <input class="form-control" id="validationCustom02" value="{{$author->bio}}" type="text" name="bio" placeholder="Bio" required="" data-bs-original-title="" title="">
                    </div>
                </div>


                <button class="btn btn-primary" type="submit" data-bs-original-title="" title="">Update</button>
            </form>
        </div>
    </div>
@endsection
