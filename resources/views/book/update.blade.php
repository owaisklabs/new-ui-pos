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
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
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
                        <label for="publisher_id">Publisher</label>

                            <select id=" publisher_id" class="form-control select2" name="publisher_id">
                                <option value="">Select Publisher</option>
                                @foreach($publishers as $publisher)
                                    <option value="{{$publisher->id}}"{{ $publisher->id == $book->publisher_id ? "selected" : " " }}>{{$publisher->name}}</option>
                                @endforeach
                            </select>


                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="author_id">Author</label>

                            <select id=" author_id" class="form-control " name="author_id[]">
                                <option value="">Select Author</option>
                                @foreach($authors as $author)
                                    <option value="{{$author->id}}"  {{ $book->authors->contains($author->id) ? "selected" : " " }} >{{$author->name}}</option>
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
                        <label for="cost_price"><span id="cost_price_text">Cost Price</span> <span class="text-danger">*</span></label>
                        <input class="form-control" id="cost_price" type="number" step="0.01" min="0" value ="{{ old('cost_price', $book->isDualUnit() ? round($book->cost_price * $book->conversion_factor, 2) : $book->cost_price) }}" name="cost_price" placeholder="Cost Price" required="" data-bs-original-title="" title="">
                        <small id="cost_price_hint" class="text-info"></small>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="sell_price"><span id="sell_price_text">Sell Price</span> <span class="text-danger">*</span></label>
                        <input class="form-control" id="sell_price" type="number" step="0.01" min="0" value ="{{ old('sell_price', $book->isDualUnit() ? round($book->sell_price * $book->conversion_factor, 2) : $book->sell_price) }}" name="sell_price" placeholder="Sell Price" required="" data-bs-original-title="" title="">
                        <small id="sell_price_hint" class="text-info"></small>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="cover_image">Cover Image</label>
                        <input class="form-control" id="cover_image" type="file"  name="cover_image" placeholder="Cover Image" data-bs-original-title="" title="">
                    </div>
                    <div class="col-12">
                        <hr>
                        <h6 class="mb-1">Dual Unit (e.g. buy in Rolls, sell in Rolls + Feet) &mdash; optional</h6>
                        <p class="text-muted mb-2">
                            Fill <strong>all three</strong> fields below, or leave all three empty for a normal product.
                            Once a conversion factor is entered, type Cost Price and Sell Price above <strong>per Roll</strong>.
                            The system divides by the conversion factor and saves the price <strong>per Foot</strong> (shown under each box).
                            Changing these on a product that already has stock? Stock is kept in the base unit (Feet), so check the stock quantity first.
                        </p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="conversion_factor">Conversion factor (e.g. 40 = 40 Feet in 1 Roll)</label>
                        <input class="form-control" id="conversion_factor" type="number" min="2" step="1" name="conversion_factor" value="{{ old('conversion_factor', $book->conversion_factor ? (float) $book->conversion_factor : '') }}" placeholder="e.g. 40">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="purchase_unit">Purchase Unit</label>
                        <input class="form-control" id="purchase_unit" type="text" name="purchase_unit" value="{{ old('purchase_unit', $book->purchase_unit) }}" placeholder="e.g. Roll">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="base_unit">Base (Sale / Stock) Unit</label>
                        <input class="form-control" id="base_unit" type="text" name="base_unit" value="{{ old('base_unit', $book->base_unit) }}" placeholder="e.g. Feet">
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

        (function () {
            const factorEl = document.getElementById('conversion_factor');
            const packEl = document.getElementById('purchase_unit');
            const baseEl = document.getElementById('base_unit');

            function refreshPrices() {
                const factor = parseFloat(factorEl.value) || 0;
                const dual = factor > 1;
                const pack = packEl.value.trim() || 'Roll';
                const base = baseEl.value.trim() || 'Feet';

                [['cost_price', 'Cost Price'], ['sell_price', 'Sell Price']].forEach(function (field) {
                    const value = parseFloat(document.getElementById(field[0]).value);
                    document.getElementById(field[0] + '_text').textContent =
                        dual ? field[1] + ' per ' + pack : field[1];
                    document.getElementById(field[0] + '_hint').textContent =
                        dual && !isNaN(value) ? '= ' + (value / factor).toFixed(2) + ' per ' + base + ' (saved)' : '';
                });
            }

            ['conversion_factor', 'purchase_unit', 'base_unit', 'cost_price', 'sell_price'].forEach(function (id) {
                document.getElementById(id).addEventListener('input', refreshPrices);
            });
            refreshPrices();
        })();
    </script>
@endsection



