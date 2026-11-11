@extends('ui.layouts.simple.master')
@section('title', 'Update Category')

@section('breadcrumb-title')
    <h3>Update Category</h3>
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
            <form class="needs-validation" method="POST" action="{{ route('categories.update', $category->id) }}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input class="form-control" id="name" type="text" name="name" value="{{ old('name', $category->name) }}" placeholder="Name" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="unit_mode">Quantity Type <span class="text-danger">*</span></label>
                        @if($category->isSystem())
                            <input type="hidden" name="unit_mode" value="{{ $category->unit_mode }}">
                            <input class="form-control" value="{{ $category->unitModeLabel() }}" disabled>
                            <small class="text-muted">This category keeps its quantity type so existing products stay on feet/roll or pieces.</small>
                        @else
                            <select class="form-control" id="unit_mode" name="unit_mode" required>
                                <option value="feet_roll" {{ old('unit_mode', $category->unit_mode) === 'feet_roll' ? 'selected' : '' }}>Feet / Roll</option>
                                <option value="pieces" {{ old('unit_mode', $category->unit_mode) === 'pieces' ? 'selected' : '' }}>Pieces</option>
                            </select>
                        @endif
                    </div>
                </div>
                <button class="btn btn-primary" type="submit">Update</button>
            </form>
        </div>
    </div>
@endsection
