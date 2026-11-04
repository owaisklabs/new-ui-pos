@extends('ui.layouts.simple.master')

@section('title', 'Expense Report')
@section('breadcrumb-title', 'Expense Report')
@section('breadcrumb-items')
    <li class="breadcrumb-item active">Reports / Expense</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h5>Expense Report</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.expense') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="branch_id">Branch</label>
                                <select name="branch_id" id="branch_id" class="form-control">
                                    <option value="">All Branches</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" @selected($branch->id == $branchId)>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="start_date">Start Date</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                            </div>

                            <div class="col-md-2">
                                <label for="end_date">End Date</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                            </div>

                            <div class="col-md-2">
                                <label for="expense_type">Type</label>
                                <select name="expense_type" id="expense_type" class="form-control">
                                    <option value="">All Types</option>
                                    <option value="utilities" @selected(request('expense_type') == 'utilities')>Utilities</option>
                                    <option value="maintenance" @selected(request('expense_type') == 'maintenance')>Maintenance</option>
                                    <option value="supplies" @selected(request('expense_type') == 'supplies')>Supplies</option>
                                    <option value="other" @selected(request('expense_type') == 'other')>Other</option>
                                </select>
                            </div>

                            <div class="col-md-4" style="padding-top: 30px;">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('reports.expense') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>

                    <div class="alert alert-info">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Total Expenses:</strong> {{ number_format($summary['total_expenses'], 2) }}
                            </div>
                            <div class="col-md-6">
                                <strong>Count:</strong> {{ $summary['count'] }}
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Branch</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Created By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expenses as $expense)
                                    <tr>
                                        <td>{{ $expense->title }}</td>
                                        <td>{{ $expense->branch->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge badge-info">{{ ucfirst($expense->expense_type ?? 'Other') }}</span>
                                        </td>
                                        <td>{{ number_format($expense->amount, 2) }}</td>
                                        <td>{{ $expense->date->format('Y-m-d') }}</td>
                                        <td>{{ $expense->user->name ?? 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No expenses found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $expenses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
