@extends('ui.layouts.simple.master')
@section('title', 'Shop Settings')

@section('breadcrumb-title')
    <h3>Shop Settings</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">Settings</li>
@endsection

@section('content')
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Business Information</h5>

                @if($setting)
                    <a href="{{ route('settings.edit', $setting) }}" class="btn btn-primary btn-sm">Edit Settings</a>
                @else
                    <a href="{{ route('settings.create') }}" class="btn btn-primary btn-sm">Create Settings</a>
                @endif
            </div>

            <div class="card-body">
                @if(!$setting)
                    <p class="mb-0">No settings added yet. Click "Create Settings" to configure your shop profile.</p>
                @else
                    <div class="row g-4">
                        <div class="col-md-4">
                            <strong>Business Title</strong>
                            <p class="mb-0">{{ $setting->business_title }}</p>
                        </div>

                        <div class="col-md-4">
                            <strong>Email</strong>
                            <p class="mb-0">{{ $setting->email }}</p>
                        </div>

                        <div class="col-md-4">
                            <strong>Mobile Number</strong>
                            <p class="mb-0">{{ $setting->mobile_number }}</p>
                        </div>

                        <div class="col-md-8">
                            <strong>Address</strong>
                            <p class="mb-0">{{ $setting->address }}</p>
                        </div>

                        <div class="col-md-4">
                            <strong>Invoice Type</strong>
                            <p class="mb-0 text-uppercase">{{ $setting->invoice_type ?? 'thermal' }}</p>
                        </div>

                        <div class="col-md-4">
                            <strong>Tax</strong>
                            <p class="mb-0">{{ $setting->tax_included ? 'Included' : 'Not Included' }}</p>
                        </div>

                        <div class="col-md-4">
                            <strong>Logo</strong>
                            @if($setting->logo)
                                <div class="mt-2">
                                    <img src="{{ $setting->logoUrl() }}" alt="Business Logo" style="max-height: 90px;">
                                </div>
                            @else
                                <p class="mb-0">No logo uploaded.</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
