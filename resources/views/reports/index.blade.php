@extends('ui.layouts.simple.master')

@section('title', 'Reports')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header pb-0">
                <h5>Reports</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-primary">
                            <div class="card-body">
                                <h5 class="card-title">Sales Report</h5>
                                <p class="card-text">View sales by branch, date range, and status</p>
                                <a href="{{ route('reports.sales') }}" class="btn btn-primary btn-sm">View Report</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-success">
                            <div class="card-body">
                                <h5 class="card-title">Inventory Report</h5>
                                <p class="card-text">Track inventory levels and low stock items</p>
                                <a href="{{ route('reports.inventory') }}" class="btn btn-success btn-sm">View Report</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-3">
                        <div class="card border-info">
                            <div class="card-body">
                                <h5 class="card-title">Stock Report</h5>
                                <p class="card-text">Complete stock details by book and branch</p>
                                <a href="{{ route('reports.stock') }}" class="btn btn-info btn-sm">View Report</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-3">
                        <div class="card border-warning">
                            <div class="card-body">
                                <h5 class="card-title">Purchase Report</h5>
                                <p class="card-text">View purchase orders and supplier transactions</p>
                                <a href="{{ route('reports.purchase') }}" class="btn btn-warning btn-sm">View Report</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-3">
                        <div class="card border-danger">
                            <div class="card-body">
                                <h5 class="card-title">Expense Report</h5>
                                <p class="card-text">Track expenses by type and date range</p>
                                <a href="{{ route('reports.expense') }}" class="btn btn-danger btn-sm">View Report</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-3">
                        <div class="card border-secondary">
                            <div class="card-body">
                                <h5 class="card-title">Branch Comparison</h5>
                                <p class="card-text">Compare performance across branches</p>
                                <a href="{{ route('reports.branch-comparison') }}" class="btn btn-secondary btn-sm">View Report</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
