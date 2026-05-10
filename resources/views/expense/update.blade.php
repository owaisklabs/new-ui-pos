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
            <form class="needs-validation" novalidate="" action="{{route('expenses.update',$expense->id)}}" METHOD="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="validationCustom01">Title</label>
                        <input class="form-control" id="validationCustom01" value="{{$expense->title}}" type="text" name="title" placeholder="Expense Title" required="" data-bs-original-title="" title="">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="validationCustom02">Expense Type</label>
                        <select id=" publisher_id" required class="form-control" name="expense_type">
                            <option value="">Select Expense Type</option>
                            <option value="utility" {{$expense->expense_type =="utility" ? "selected":" "}}>Utility</option>
                            <option value="salary" {{$expense->expense_type =="salary" ? "selected":" "}}>Salary</option>
                            <option value="others" {{$expense->expense_type =="others" ? "selected":" "}}>Others</option>

                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="validationCustom02">Amount</label>
                        <input class="form-control" id="validationCustom02" value="{{$expense->amount}}" type="number" step="0.01" name="amount" placeholder="Expense Amounts" required="" data-bs-original-title="" title="">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="validationCustom02">Expense Date</label>
                        <input class="form-control" id="validationCustom02" type="date" value="{{$expense->date}}" name="date" placeholder="Bio" required="" data-bs-original-title="" title="">
                    </div>
                </div>


                <button class="btn btn-primary" type="submit" data-bs-original-title="" title="">Update</button>
            </form>
        </div>
    </div>
@endsection
