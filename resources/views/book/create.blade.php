@extends('ui.layouts.simple.master')
@section('title', 'Bootstrap Border Table')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>Create Item</h3>


@endsection


@section('content')
    <div class="card">

        <div class="card-body">
            <form class="needs-validation"  action="{{route('book.store')}}" METHOD="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="sku">SKU <span class="text-danger">*</span></label>
                        <input class="form-control abc" id="sku" type="text" name="sku" placeholder="SKU" required="" data-bs-original-title="" title="">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="bar_code">Barcode <span class="text-danger">*</span></label>
                        <input class="form-control" id="bar_code" type="text" name="bar_code" placeholder="Barcode" required="" data-bs-original-title="" title="">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <input class="form-control" id="title" type="text" name="title" placeholder="Title" required="" data-bs-original-title="" title="">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="subtitle">Sub Title</label>
                        <input class="form-control" id="subtitle" type="text" name="subtitle" placeholder="Sub Title"  data-bs-original-title="" title="">
                    </div>



                    <div class="col-md-3 mb-3">
                        <label for="description">Description</label>
                        <input class="form-control" id="description" type="text" name="description" placeholder="Description"  data-bs-original-title="" title="">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="cost_price">Cost Price <span class="text-danger">*</span></label>
                        <input class="form-control" id="cost_price" type="number" name="cost_price" placeholder="Cost Price" required="" data-bs-original-title="" title="">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="sell_price">Sell Price <span class="text-danger">*</span></label>
                        <input class="form-control" id="sell_price" type="number" name="sell_price" placeholder="Sell Price" required="" data-bs-original-title="" title="">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="cover_image">Cover Image <span class="text-danger">*</span></label>
                        <input class="form-control" id="cover_image" type="file" name="cover_image" placeholder="Cover Image" required="" data-bs-original-title="" title="">
                    </div>

                    <img id="preview-image" src="#" alt="Preview" style="max-width: 250px; display: none; border: 1px solid #ccc; padding: 5px;">

                </div>


                <button class="btn btn-primary" type="submit" data-bs-original-title="" title="">Create</button>
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



