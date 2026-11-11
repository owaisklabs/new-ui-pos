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
                        @php
                            $selectedCategory = old('category_id', $book->category_id);
                            if (!$selectedCategory && $book->isDualUnit()) {
                                $selectedCategory = optional($categories->firstWhere('slug', 'feet-roll'))->id;
                            }
                        @endphp
                        <label for="category_id">Category <span class="text-danger">*</span></label>
                        <select class="form-control" id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" data-mode="{{ $category->unit_mode }}" {{ (string) $selectedCategory === (string) $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <small id="pieces_hint" class="text-info"></small>
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
                        <label for="cover_image">Cover Image</label>
                        <input class="form-control" id="cover_image" type="file"  name="cover_image" placeholder="Cover Image" data-bs-original-title="" title="">
                    </div>
                    <div class="col-12" id="dual-unit-fields">
                        <hr>
                        <h6 class="mb-1">Feet / Roll</h6>
                        <p class="text-muted mb-2">
                            Enter how many Feet are in one Roll. Type Cost Price <strong>per Roll</strong>.
                            The system saves the price <strong>per Foot</strong> (shown under the cost box).
                            Changing a product that already has stock does not convert the stored quantity, so check stock first.
                        </p>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="conversion_factor">Conversion factor (e.g. 40 = 40 Feet in 1 Roll)</label>
                                <input class="form-control" id="conversion_factor" type="number" min="2" step="1" name="conversion_factor" value="{{ old('conversion_factor', $book->conversion_factor ? (float) $book->conversion_factor : '') }}" placeholder="e.g. 40">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="purchase_unit">Purchase Unit</label>
                                <input class="form-control" id="purchase_unit" type="text" name="purchase_unit" value="{{ old('purchase_unit', $book->purchase_unit ?: 'Roll') }}" placeholder="e.g. Roll">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="base_unit">Base (Sale / Stock) Unit</label>
                                <input class="form-control" id="base_unit" type="text" name="base_unit" value="{{ old('base_unit', $book->base_unit ?: 'Feet') }}" placeholder="e.g. Feet">
                            </div>
                        </div>
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
            const categoryEl = document.getElementById('category_id');
            const factorEl = document.getElementById('conversion_factor');
            const packEl = document.getElementById('purchase_unit');
            const baseEl = document.getElementById('base_unit');
            const dualEl = document.getElementById('dual-unit-fields');
            const piecesHint = document.getElementById('pieces_hint');

            function selectedMode() {
                const opt = categoryEl.options[categoryEl.selectedIndex];
                return opt ? (opt.getAttribute('data-mode') || '') : '';
            }

            function refreshPrices() {
                const mode = selectedMode();
                const pieces = mode === 'pieces';
                const dual = mode === 'feet_roll';
                dualEl.style.display = dual ? '' : 'none';
                factorEl.required = dual;
                packEl.required = dual;
                baseEl.required = dual;
                factorEl.disabled = !dual;
                packEl.disabled = !dual;
                baseEl.disabled = !dual;

                if (pieces) {
                    document.getElementById('cost_price_text').textContent = 'Cost Price per Piece';
                    document.getElementById('cost_price_hint').textContent = 'Saved per piece. Quantity is counted in pieces.';
                    piecesHint.textContent = 'Sales stay on the sales screen and are not listed on this item, same as feet/roll items.';
                    return;
                }

                piecesHint.textContent = '';
                const factor = parseFloat(factorEl.value) || 0;
                const pack = packEl.value.trim() || 'Roll';
                const base = baseEl.value.trim() || 'Feet';
                const value = parseFloat(document.getElementById('cost_price').value);
                document.getElementById('cost_price_text').textContent = dual ? 'Cost Price per ' + pack : 'Cost Price';
                document.getElementById('cost_price_hint').textContent =
                    dual && factor > 1 && !isNaN(value) ? '= ' + (value / factor).toFixed(2) + ' per ' + base + ' (saved)' : '';
            }

            ['category_id', 'conversion_factor', 'purchase_unit', 'base_unit', 'cost_price'].forEach(function (id) {
                document.getElementById(id).addEventListener('input', refreshPrices);
                document.getElementById(id).addEventListener('change', refreshPrices);
            });
            refreshPrices();
        })();
    </script>
@endsection



