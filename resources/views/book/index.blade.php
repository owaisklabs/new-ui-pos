@extends('ui.layouts.simple.master')
@section('title', 'Bootstrap Border Table')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>Items</h3>
@endsection

{{--@section('breadcrumb-items')--}}
{{--    <li class="breadcrumb-item">Authors</li>--}}
{{--@endsection--}}


@section('content')
    <div class="container-fluid">
        {{-- <div class="row"> --}}
        <form action="#" class="row" method="GET">
            <div class="col-2 mb-3">
                <label for="validationCustom01">Bar Code</label>
                <input class="form-control" name="query" type="text" placeholder="Bar Code" required="">
            </div>
            <div class="col-2 mb-3">
                <label for="validationCustom01">Title</label>
                <input class="form-control" name="query" type="text" placeholder="Tittle" required="">
            </div>

            <div class="col-2 mb-3">
                <label for="validationCustom02">From Date</label>
                <input class="form-control" name="from_date" id="validationCustom02" type="date"  >
            </div>
            <div class="col-2 mb-3">
                <label for="validationCustom02">To Date</label>
                <input class="form-control" name="to_date" id="validationCustom02" type="date"  >
            </div>
            <div class="col-1 mb-3" style="margin-top: 25px;">
                <button class="btn  btn-primary btn-air-primary btn-lg" type="submit">Search</button>
            </div>
            <div class="col-1 mb-3" style="margin-top: 25px;">
                <button class="btn  btn-warning btn-air-warning btn-lg" type="submit">Clear</button>
            </div>
            <div class="col-2 mb-3" style="margin-top: 25px;">
                <a href="{{route('book.create')}}" class="btn  btn-success btn-air-success btn-lg" type="submit">Create Item</a>
            </div>
        </form>
        {{-- </div> --}}
        <div class="row">

            <div class="col-sm-12">

                <div class="table-responsive">
                    <table class="table table-border-vertical " >
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">SKU</th>
                            <th scope="col">Title</th>
                            <th scope="col">Qty</th>
                            <th scope="col">Cost Price</th>
                            <th scope="col">Sell price</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($books as $item)
                        <tr>
                            <th scope="row">{{$item->id}}</th>
                            <td>{{$item->sku}}</td>
                            <td>{{$item->title}}</td>

                            <td>{{$item->inventory->quantity ?? "-"}}</td>
                            <td>{{$item->cost_price}}</td>
                            <td>{{$item->sell_price}}</td>
                            <td class="d-flex align-items-center gap-2">

                                {{-- Delete --}}
                                <form action="{{ route('book.destroy', $item->id) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this book?');" class="m-0 p-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger p-1 d-flex align-items-center justify-content-center">
                                        <i data-feather="trash-2"></i>
                                    </button>
                                </form>

                                {{-- Edit --}}
                                <a href="{{ route('book.edit', $item->id) }}" class="btn btn-sm btn-primary p-1 d-flex align-items-center justify-content-center">
                                    <i data-feather="edit"></i>
                                </a>

                                {{-- Sales --}}
                                <a href="{{ route('get-book-sales-by-id', $item->id) }}" class="btn btn-sm btn-info p-1 d-flex align-items-center justify-content-center">
                                    <i data-feather="shopping-cart"></i>
                                </a>

                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $books->links('pagination::bootstrap-5')}}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection
