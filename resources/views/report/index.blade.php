@extends('ui.layouts.simple.master')
@section('title', 'Reports')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>Reports</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Reports</li>
@endsection

@section('content')
    <div class="container-fluid">
        <form action="{{ url('/dashboard/reports') }}" method="GET" class="row mb-4">
            <div class="col-md-4 mb-3">
                <label for="from">From Date</label>
                <input class="form-control" name="from" id="from" type="date" 
                       value="{{ request('from') ?? now()->startOfMonth()->format('Y-m-d') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label for="to">To Date</label>
                <input class="form-control" name="to" id="to" type="date" 
                       value="{{ request('to') ?? now()->endOfMonth()->format('Y-m-d') }}">
            </div>
            <div class="col-md-4 mb-3" style="margin-top: 25px;">
                <button class="btn btn-pill btn-primary btn-air-primary btn-lg" type="submit">Generate Report</button>
            </div>
        </form>

        <div class="row">
            <div class="col-xl-3 col-md-6 box-col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="media-body right-chart-content">
                                <h4>{{ number_format($totalSales ?? 0, 2) }}</h4>
                                <span>Total Sales</span>
                            </div>
                            <div class="knob-block text-center">
                                <i data-feather="trending-up" class="text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 box-col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="media-body right-chart-content">
                                <h4>{{ number_format($totalCost ?? 0, 2) }}</h4>
                                <span>Total Cost</span>
                            </div>
                            <div class="knob-block text-center">
                                <i data-feather="shopping-cart" class="text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 box-col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="media-body right-chart-content">
                                <h4>{{ number_format($totalExpenses ?? 0, 2) }}</h4>
                                <span>Total Expenses</span>
                            </div>
                            <div class="knob-block text-center">
                                <i data-feather="dollar-sign" class="text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 box-col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="media-body right-chart-content">
                                <h4 class="{{ ($profitLoss ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($profitLoss ?? 0, 2) }}
                                </h4>
                                <span>Profit / Loss</span>
                            </div>
                            <div class="knob-block text-center">
                                <i data-feather="{{ ($profitLoss ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' }}" 
                                   class="{{ ($profitLoss ?? 0) >= 0 ? 'text-success' : 'text-danger' }}"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Financial Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Total Sales</strong></td>
                                        <td class="text-end"><strong>{{ number_format($totalSales ?? 0, 2) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Total Cost</td>
                                        <td class="text-end text-danger">- {{ number_format($totalCost ?? 0, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Total Expenses</td>
                                        <td class="text-end text-danger">- {{ number_format($totalExpenses ?? 0, 2) }}</td>
                                    </tr>
                                    <tr class="table-{{ ($profitLoss ?? 0) >= 0 ? 'success' : 'danger' }}">
                                        <td><strong>Net Profit / Loss</strong></td>
                                        <td class="text-end"><strong>{{ number_format($profitLoss ?? 0, 2) }}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection

