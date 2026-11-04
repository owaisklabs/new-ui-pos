@extends('ui.layouts.simple.master')
@section('title', $setting ? 'Edit Shop Settings' : 'Create Shop Settings')

@section('breadcrumb-title')
    <h3>{{ $setting ? 'Edit Shop Settings' : 'Create Shop Settings' }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">Settings</a></li>
    <li class="breadcrumb-item active">{{ $setting ? 'Edit' : 'Create' }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form action="{{ $setting ? route('settings.update', $setting) : route('settings.store') }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf

                    @if($setting)
                        @method('PUT')
                    @endif

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="business_title">Business Title <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('business_title') is-invalid @enderror"
                                   id="business_title"
                                   name="business_title"
                                   value="{{ old('business_title', $setting->business_title ?? '') }}"
                                   required>
                            @error('business_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email', $setting->email ?? '') }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="mobile_number">Mobile Number <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('mobile_number') is-invalid @enderror"
                                   id="mobile_number"
                                   name="mobile_number"
                                   value="{{ old('mobile_number', $setting->mobile_number ?? '') }}"
                                   required>
                            @error('mobile_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="logo">Business Logo</label>
                            <input type="file"
                                   class="form-control @error('logo') is-invalid @enderror"
                                   id="logo"
                                   name="logo"
                                   accept=".jpg,.jpeg,.png,.webp">
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label" for="address">Address <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address"
                                      name="address"
                                      rows="3"
                                      required>{{ old('address', $setting->address ?? '') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="invoice_type">Invoice Type <span class="text-danger">*</span></label>
                            <select id="invoice_type"
                                    name="invoice_type"
                                    class="form-control @error('invoice_type') is-invalid @enderror"
                                    required>
                                @php
                                    $selectedInvoiceType = old('invoice_type', $setting->invoice_type ?? 'thermal');
                                @endphp
                                <option value="thermal" {{ $selectedInvoiceType === 'thermal' ? 'selected' : '' }}>Thermal</option>
                                <option value="a5" {{ $selectedInvoiceType === 'a5' ? 'selected' : '' }}>A5</option>
                            </select>
                            @error('invoice_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label d-block">Tax</label>
                            <div class="form-check mt-2">
                                <input class="form-check-input"
                                       type="checkbox"
                                       id="tax_included"
                                       name="tax_included"
                                       value="1"
                                       {{ old('tax_included', (int) ($setting->tax_included ?? 0)) ? 'checked' : '' }}>
                                <label class="form-check-label" for="tax_included">
                                    Tax Included
                                </label>
                            </div>
                            <small class="text-muted">If checked, invoice will show tax as included.</small>
                        </div>

                        @if($setting && $setting->logo)
                            <div class="col-md-12">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $setting->logoUrl() }}" alt="Current Logo" style="max-height: 70px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="remove_logo" name="remove_logo" value="1">
                                        <label class="form-check-label" for="remove_logo">
                                            Remove current logo
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            {{ $setting ? 'Update Settings' : 'Save Settings' }}
                        </button>
                        <a href="{{ route('settings.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
