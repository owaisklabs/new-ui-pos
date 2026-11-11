@extends('ui.layouts.simple.master')
@section('title', 'Create Category')

@section('breadcrumb-title')
    <h3>Create Category</h3>
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
            <form class="needs-validation" method="POST" action="{{ route('categories.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input class="form-control" id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Name" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="unit_mode">Quantity Type <span class="text-danger">*</span></label>
                        <select class="form-control" id="unit_mode" name="unit_mode" required>
                            <option value="">Select quantity type</option>
                            <option value="feet_roll" {{ old('unit_mode') === 'feet_roll' ? 'selected' : '' }}>Feet / Roll</option>
                            <option value="pieces" {{ old('unit_mode') === 'pieces' ? 'selected' : '' }}>Pieces</option>
                        </select>
                        <small class="text-muted">Feet / Roll keeps roll and feet quantities. Pieces uses a simple piece count. Sales stay on the sales screen for both.</small>
                    </div>
                </div>
                <button class="btn btn-primary" type="submit">Create</button>
            </form>
        </div>
    </div>
@endsection
