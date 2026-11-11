@extends('ui.layouts.simple.master')
@section('title', 'Categories')

@section('breadcrumb-title')
    <h3>Categories</h3>
@endsection

@section('content')
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row mb-3">
            <div class="col-12 text-end">
                <a href="{{ route('categories.create') }}" class="btn btn-success btn-air-success">Create Category</a>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-border-vertical">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Quantity Type</th>
                            <th scope="col">Products</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($categories as $item)
                            <tr>
                                <th scope="row">{{ $item->id }}</th>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->unitModeLabel() }}</td>
                                <td>{{ $item->books_count }}</td>
                                <td class="d-flex align-items-center gap-2">
                                    <a href="{{ route('categories.edit', $item->id) }}" class="btn btn-sm btn-primary p-1 d-flex align-items-center justify-content-center">
                                        <i data-feather="edit"></i>
                                    </a>
                                    @unless($item->isSystem())
                                        <form action="{{ route('categories.destroy', $item->id) }}" method="POST"
                                              onsubmit="return confirm('Delete this category?');" class="m-0 p-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger p-1 d-flex align-items-center justify-content-center">
                                                <i data-feather="trash-2"></i>
                                            </button>
                                        </form>
                                    @endunless
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No categories yet.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    {{ $categories->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
