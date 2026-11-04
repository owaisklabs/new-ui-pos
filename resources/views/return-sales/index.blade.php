@extends('ui.layouts.simple.master')
@section('title', 'Sale Returns')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Sale Returns</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Return Invoice</th>
                            <th>Sale Invoice</th>
                            <th>Customer</th>
                            <th>Return Amount</th>
                            <th>Items</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($returns as $index => $return)
                        <tr>
                            <td>{{ $returns->firstItem() + $index }}</td>
                            <td>{{ $return->return_invoice_no }}</td>
                            <td>{{ $return->sale->invoice_no ?? 'N/A' }}</td>
                            <td>{{ $return->sale->customer->name ?? 'N/A' }}</td>
                            <td>{{ number_format($return->total_return_amount, 2) }}</td>
                            <td>{{ $return->items->sum('quantity') }}</td>
                            <td>{{ $return->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <a href="{{ route('return-sales.show', $return) }}" class="btn btn-sm btn-primary p-1">
                                    <i data-feather="eye"></i>
                                </a>
                                @if($return->sale)
                                    <a href="{{ route('sales.show', $return->sale) }}" class="btn btn-sm btn-secondary p-1">
                                        <i data-feather="file-text"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No sale returns found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{ $returns->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
