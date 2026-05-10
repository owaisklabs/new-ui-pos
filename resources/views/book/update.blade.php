@extends('ui.layouts.simple.master')
@section('title', 'Bootstrap Border Table')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>Update Book</h3>


@endsection


@section('content')
    <div class="card">

        <div class="card-body">
            <form class="needs-validation"  action="{{route('book.update',$book->id)}}" METHOD="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="sku">SKU <span class="text-danger">*</span></label>
                        <input class="form-control abc" id="sku" type="text"  value ="{{$book->sku}}" name="sku" placeholder="SKU" required="" data-bs-original-title="" title="">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="bar_code">Barcode <span class="text-danger">*</span></label>
                        <input class="form-control" id="bar_code" type="text" value ="{{$book->bar_code}}" name="bar_code" placeholder="Barcode" required="" data-bs-original-title="" title="">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <input class="form-control" id="title" type="text" value ="{{$book->title}}" name="title" placeholder="Title" required="" data-bs-original-title="" title="">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="subtitle">Sub Title</label>
                        <input class="form-control" id="subtitle" type="text" value ="{{$book->subtitle}}" name="subtitle" placeholder="Sub Title"  data-bs-original-title="" title="">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="publisher_id">Publisher <span class="text-danger">*</span></label>

                            <select id=" publisher_id" required class="form-control select2" name="publisher_id">
                                <option value="">Select Publisher</option>
                                @foreach($publishers as $publisher)
                                    <option value="{{$publisher->id}}"{{ $publisher->id == $book->publisher_id ? "selected" : " " }}>{{$publisher->name}}</option>
                                @endforeach
                            </select>


                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="author_id">Author <span class="text-danger">*</span></label>

                            <select id=" author_id" required class="form-control " name="author_id[]">
                                <option value="">Select Publisher</option>
                                @foreach($authors as $author)
                                    <option value="{{$author->id}}"  {{ $author->id == $book->authors[0]->id ? "selected" : " " }} >{{$author->name}}</option>
                                @endforeach
                            </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="published_at">Published At</label>
                        <input class="form-control" id="published_at" type="date" value ="{{$book->published_at}}" name="published_at" placeholder="Published At"  data-bs-original-title="" title="">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="description">Description</label>
                        <input class="form-control" id="description" type="text" value ="{{$book->description}}" name="description" placeholder="Description"  data-bs-original-title="" title="">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="cost_price">Cost Price <span class="text-danger">*</span></label>
                        <input class="form-control" id="cost_price" type="number" value ="{{$book->cost_price}}" name="cost_price" placeholder="Cost Price" required="" data-bs-original-title="" title="">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="sell_price">Sell Price <span class="text-danger">*</span></label>
                        <input class="form-control" id="sell_price" type="number" value ="{{$book->sell_price}}" name="sell_price" placeholder="Sell Price" required="" data-bs-original-title="" title="">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="cover_image">Cover Image <span class="text-danger">*</span></label>
                        <input class="form-control" id="cover_image" type="file"  name="cover_image" placeholder="Cover Image" required="" data-bs-original-title="" title="">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="pages">Pages</label>
                        <input class="form-control" id="pages" type="number" value ="{{$book->pages}}" name="pages" placeholder="Pages"  data-bs-original-title="" title="">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="language">Language</label>
                        <input class="form-control" id="language" type="text" value ="{{$book->language}}" name="language" placeholder="Language"  data-bs-original-title="" title="">
                    </div>
                    <img id="preview-image" src="#" alt="Preview" style="max-width: 250px; display: none; border: 1px solid #ccc; padding: 5px;">

                </div>


                <button class="btn btn-primary" type="submit" data-bs-original-title="" title="">Update</button>
            </form>
        </div>
    </div>
@endsection

    <style>

    </style>


@section('script')
    <script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
    <script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
    <script>
        // $(document).ready(function() {
        //     $('.select2').select2({
        //         placeholder: "Select an option",
        //         allowClear: true
        //     });
        // });
        document.getElementById('cover_image').addEventListener('change', function(event){
            const file = event.target.files[0];

            if (file) {
                const preview = document.getElementById('preview-image');
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
            }
        });
    </script>
@endsection



