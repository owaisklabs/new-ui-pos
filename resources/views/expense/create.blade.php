@extends('ui.layouts.simple.master')
@section('title', 'Bootstrap Border Table')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>Create Expense</h3>


@endsection


@section('content')
    <div class="card">

        <div class="card-body">
            <form class="needs-validation" novalidate="" action="{{route('expenses.store')}}" METHOD="post">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="validationCustom01">Title</label>
                        <input class="form-control" id="validationCustom01" type="text" name="title" placeholder="Expense Title" required="" data-bs-original-title="" title="">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="validationCustom02">Expense Type</label>
                        <select id=" publisher_id" required class="form-control " name="expense_type">
                            <option value="">Select Expense Type</option>
                            <option value="utility">Utility</option>
                            <option value="salary">Salary</option>
                            <option value="others">Others</option>

                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="validationCustom02">Amount</label>
                        <input class="form-control" id="validationCustom02" type="number" step="0.01" name="amount" placeholder="Expense Amounts" required="" data-bs-original-title="" title="">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="validationCustom02">Expense Date</label>
                        <input class="form-control" id="validationCustom02" type="date" name="date" placeholder="Bio" required="" data-bs-original-title="" title="">
                    </div>
                </div>


                <button class="btn btn-primary" type="submit" data-bs-original-title="" title="">Create</button>
            </form>
        </div>
    </div>
@endsection
