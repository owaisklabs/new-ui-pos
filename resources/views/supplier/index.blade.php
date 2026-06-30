@extends('ui.layouts.simple.master')
@section('title', 'Bootstrap Border Table')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Suppliers</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Suppliers</li>
@endsection

@section('content')
<div class="container-fluid">
    {{-- <div class="row"> --}}
        <form action="#" class="row" method="GET">
            <div class="col-md-3 mb-3">
                <label for="validationCustom01">Query</label>
                <input class="form-control" name="query" type="text" placeholder="First name" required="">
                <div class="valid-feedback">Looks good!</div>
            </div>
            <div class="col-md-2 mb-3">
                <label for="validationCustom02">From Date</label>
                <input class="form-control" name="from_date" id="validationCustom02" type="date"  >
            </div>
            <div class="col-md-2 mb-3">
                <label for="validationCustom02">To Date</label>
                <input class="form-control" name="to_date" id="validationCustom02" type="date"  >
            </div>
            <div class="col-md-1 mb-3" style="margin-top: 25px;">
                <button class="btn  btn-primary btn-air-primary btn-lg" type="submit">Search</button>
            </div>
            <div class="col-md-1 mb-3" style="margin-top: 25px;">
                <button class="btn  btn-warning btn-air-warning btn-lg" onclick="clearSearch()">Clear</button>
            </div>
            <div class="col-md-2 mb-3" style="margin-top: 25px;">
                <a href="{{route('supplier.create')}}" class="btn  btn-success btn-air-success btn-lg">Create Supplier</a>
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
								<th scope="col">Name</th>
								<th scope="col">Contact Person</th>
								<th scope="col">Phone</th>
								<th scope="col">Email</th>
								<th scope="col">Created At</th>
								<th scope="col">Action</th>
							</tr>
						</thead>
						<tbody>
                        @foreach($suppliers as $item)
                            <tr>
                                <th scope="row">{{$item->id}}</th>
                                <td>{{$item->name}}</td>
                                <td>{{$item->contact_person}}</td>
                                <td>{{$item->phone}}</td>
                                <td>{{$item->email}}</td>
                                <td>{{$item->created_at}}</td>
                                <td class="d-flex align-items-center gap-2">

                                    {{-- Delete --}}
                                    <form action="{{ route('supplier.destroy', $item->id) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this supplier?');" class="m-0 p-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger p-1 d-flex align-items-center justify-content-center">
                                            <i data-feather="trash-2"></i>
                                        </button>
                                    </form>

                                    {{-- Edit --}}
                                    <a href="{{ route('supplier.edit', $item->id) }}" class="btn btn-sm btn-primary p-1 d-flex align-items-center justify-content-center">
                                        <i data-feather="edit"></i>
                                    </a>

                                </td>
                            </tr>
                        @endforeach



						</tbody>
					</table>
				</div>
            {{ $suppliers->links('pagination::bootstrap-5')}}
		</div>
	</div>
</div>
@endsection

@section('script')
@endsection
