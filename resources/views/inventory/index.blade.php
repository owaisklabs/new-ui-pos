@extends('ui.layouts.simple.master')
@section('title', 'Inventory')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>Inventory</h3>
@endsection

{{--@section('breadcrumb-items')--}}
{{--    <li class="breadcrumb-item">Authors</li>--}}
{{--@endsection--}}


@section('content')
    <div class="container-fluid">
        {{-- <div class="row"> --}}
        <form action="#" class="row " id="search-form" method="GET">
            <div class="col-md-3 mb-3">
                <label for="validationCustom01">Query</label>
                <input class="form-control" name="query[name]" type="text" placeholder="First name" >
                <div class="valid-feedback">Looks good!</div>
            </div>
            <div class="col-md-3 mb-3">
                <label for="validationCustom02">From Date</label>
                <input class="form-control" name="query[from_date]" value="{{ request('query.from_date') }}" id="validationCustom02" type="date"  >
            </div>
            <div class="col-md-3 mb-3">
                <label for="validationCustom02">To Date</label>
                <input class="form-control" name="query[to_date]" value="{{ request('query.to_date') }}" id="validationCustom02" type="date"  >
            </div>
            <div class="col-md-2 mb-3" style="margin-top: 25px;">
                <button class="btn btn-pill btn-primary btn-air-primary btn-lg" type="submit">Search</button>
            </div>
            <div class="col-md-1 mb-3" style="margin-top: 25px;">
                <button class="btn btn-pill btn-primary btn-air-primary btn-lg" onclick="clearSearch()">Clear</button>
            </div>
        </form>
        {{-- </div> --}}

        <div class="row">

            <div class="col-sm-12">

                <div class="table-responsive">
                    <table class="table table-border-vertical " style="background-color: white;">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Location</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($inventories as $item)
                        <tr>
                            <td scope="row">{{$item->id}}</td>
                            <td>{{$item->book->title}}</td>
                            <td>{{$item->quantity}}</td>
                            <td>{{$item->location}}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $inventories->links('pagination::bootstrap-5')}}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var BASE_URL = "{{ url('') }}";

        function clearSearch() {
            // Clear text input
            $("input[name='query[invoice_no]']").val("");

            // Clear customer dropdown
            $("#customer_id").val("");

            // Clear dates
            $("input[name='query[from_date]']").val("");
            $("input[name='query[to_date]']").val("");

            // Submit the form after clearing
            $("#search-form").submit();
        }

    </script>
@endsection
