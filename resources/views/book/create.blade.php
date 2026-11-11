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
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form class="needs-validation"  action="{{route('book.store')}}" METHOD="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    
                    <div class="col-md-3 mb-3">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <input class="form-control" id="title" type="text" name="title" value="{{ old('title') }}" placeholder="Title" required="" data-bs-original-title="" title="">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="category_id">Category <span class="text-danger">*</span></label>
                        <select class="form-control" id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" data-mode="{{ $category->unit_mode }}" {{ (string) old('category_id') === (string) $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <small id="pieces_hint" class="text-info"></small>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label for="cost_price"><span id="cost_price_text">Cost Price</span> <span class="text-danger">*</span></label>
                        <input class="form-control" id="cost_price" type="number" step="0.01" min="0" name="cost_price" value="{{ old('cost_price') }}" placeholder="Cost Price" required="" data-bs-original-title="" title="">
                        <small id="cost_price_hint" class="text-info"></small>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="cover_image">Cover Image <span class="text-danger">*</span></label>
                        <input class="form-control" id="cover_image" type="file" name="cover_image" placeholder="Cover Image" required="" data-bs-original-title="" title="">
                    </div>

                    <div class="col-12" id="dual-unit-fields">
                        <hr>
                        <h6 class="mb-1">Feet / Roll</h6>
                        <p class="text-muted mb-2">
                            Enter how many Feet are in one Roll. Type Cost Price <strong>per Roll</strong>.
                            The system saves the price <strong>per Foot</strong> (shown under the cost box).
                            Purchases are in rolls. Sales can be rolls, feet, or both.
                        </p>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="conversion_factor">Conversion factor (e.g. 40 = 40 Feet in 1 Roll)</label>
                                <input class="form-control" id="conversion_factor" type="number" min="2" step="1" name="conversion_factor" value="{{ old('conversion_factor') }}" placeholder="e.g. 40">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="purchase_unit">Purchase Unit</label>
                                <input class="form-control" id="purchase_unit" type="text" name="purchase_unit" value="{{ old('purchase_unit', 'Roll') }}" placeholder="e.g. Roll">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="base_unit">Base (Sale / Stock) Unit</label>
                                <input class="form-control" id="base_unit" type="text" name="base_unit" value="{{ old('base_unit', 'Feet') }}" placeholder="e.g. Feet">
                            </div>
                        </div>
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



