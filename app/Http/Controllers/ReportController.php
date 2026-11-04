<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Expense;
use App\Models\Inventory;
use App\Models\Book;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $branches = $user->isAdmin() ? Branch::where('is_active', true)->get() : $user->getAccessibleBranches();

        return view('reports.index', compact('branches', 'user'));
    }

    public function salesReport(Request $request)
    {
        $user = Auth::user();
        $branchId = $request->get('branch_id');
        $branches = $user->isAdmin() ? Branch::where('is_active', true)->get() : $user->getAccessibleBranches();

        if ($branchId && !$user->isAdmin() && !$user->canAccessBranch($branchId)) {
            return redirect()->route('reports.index')->with('error', 'Unauthorized access');
        }

        $query = Sale::with('customer', 'user', 'branch');

        if ($branchId) {
            $query->where('branch_id', $branchId);
        } elseif (!$user->isAdmin()) {
            $query->whereIn('branch_id', $user->getAccessibleBranches()->pluck('id'));
        }

        if ($request->has('start_date') && $request->get('start_date')) {
            $query->whereDate('sale_date', '>=', $request->get('start_date'));
        }

        if ($request->has('end_date') && $request->get('end_date')) {
            $query->whereDate('sale_date', '<=', $request->get('end_date'));
        }

        if ($request->has('status') && $request->get('status')) {
            $query->where('status', $request->get('status'));
        }

        $sales = $query->orderBy('sale_date', 'desc')->paginate(50);

        $summary = [
            'total_sales' => $query->sum('total_amount'),
            'total_paid' => $query->sum('paid_amount'),
            'total_outstanding' => $query->sum('total_amount') - $query->sum('paid_amount'),
            'count' => $query->count(),
        ];

        return view('reports.sales', compact('sales', 'summary', 'branches', 'branchId'));
    }

    public function inventoryReport(Request $request)
    {
        $user = Auth::user();
        $branchId = $request->get('branch_id');
        $branches = $user->isAdmin() ? Branch::where('is_active', true)->get() : $user->getAccessibleBranches();

        if ($branchId && !$user->isAdmin() && !$user->canAccessBranch($branchId)) {
            return redirect()->route('reports.index')->with('error', 'Unauthorized access');
        }

        $query = Inventory::with('book', 'branch');

        if ($branchId) {
            $query->where('branch_id', $branchId);
        } elseif (!$user->isAdmin()) {
            $query->whereIn('branch_id', $user->getAccessibleBranches()->pluck('id'));
        }

        $lowStock = $request->get('low_stock') == 'on';
        if ($lowStock) {
            $query->lowStock();
        }

        $inventory = $query->with('book')
            ->orderBy('quantity', 'asc')
            ->paginate(50);

        // Calculate total value in PHP instead of SQL
        $totalValue = 0;
        foreach($inventory as $item) {
            $totalValue += ($item->quantity * ($item->book->cost_price ?? 0));
        }

        $summary = [
            'total_items' => Inventory::when($branchId, function($q) use ($branchId) {
                return $q->where('branch_id', $branchId);
            })->when(!$user->isAdmin() && !$branchId, function($q) use ($user) {
                return $q->whereIn('branch_id', $user->getAccessibleBranches()->pluck('id'));
            })->count(),
            'total_value' => $totalValue,
            'low_stock_count' => Inventory::when($branchId, function($q) use ($branchId) {
                return $q->where('branch_id', $branchId);
            })->when(!$user->isAdmin() && !$branchId, function($q) use ($user) {
                return $q->whereIn('branch_id', $user->getAccessibleBranches()->pluck('id'));
            })->lowStock()->count(),
        ];

        return view('reports.inventory', compact('inventory', 'summary', 'branches', 'branchId', 'lowStock'));
    }

    public function stockReport(Request $request)
    {
        $user = Auth::user();
        $branchId = $request->get('branch_id');
        $branches = $user->isAdmin() ? Branch::where('is_active', true)->get() : $user->getAccessibleBranches();

        if (!$user->isAdmin() && $branchId && !$user->canAccessBranch($branchId)) {
            return redirect()->route('reports.index')->with('error', 'Unauthorized access');
        }

        $query = Book::with('inventories');

        if ($branchId) {
            $query->whereHas('inventories', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        } elseif (!$user->isAdmin()) {
            $accessibleBranchIds = $user->getAccessibleBranches()->pluck('id')->toArray();
            $query->whereHas('inventories', function ($q) use ($accessibleBranchIds) {
                $q->whereIn('branch_id', $accessibleBranchIds);
            });
        }

        $books = $query->with(['inventories' => function ($q) use ($branchId) {
            if ($branchId) {
                $q->where('branch_id', $branchId);
            }
        }])->paginate(50);

        $summary = [
            'total_books' => $query->count(),
            'total_stock_value' => 0,
        ];

        return view('reports.stock', compact('books', 'summary', 'branches', 'branchId'));
    }

    public function purchaseReport(Request $request)
    {
        $user = Auth::user();
        $branchId = $request->get('branch_id');
        $branches = $user->isAdmin() ? Branch::where('is_active', true)->get() : $user->getAccessibleBranches();

        if ($branchId && !$user->isAdmin() && !$user->canAccessBranch($branchId)) {
            return redirect()->route('reports.index')->with('error', 'Unauthorized access');
        }

        $query = Purchase::with('supplier', 'user', 'branch');

        if ($branchId) {
            $query->where('branch_id', $branchId);
        } elseif (!$user->isAdmin()) {
            $query->whereIn('branch_id', $user->getAccessibleBranches()->pluck('id'));
        }

        if ($request->has('start_date') && $request->get('start_date')) {
            $query->whereDate('purchase_date', '>=', $request->get('start_date'));
        }

        if ($request->has('end_date') && $request->get('end_date')) {
            $query->whereDate('purchase_date', '<=', $request->get('end_date'));
        }

        if ($request->has('status') && $request->get('status')) {
            $query->where('status', $request->get('status'));
        }

        $purchases = $query->orderBy('purchase_date', 'desc')->paginate(50);

        $summary = [
            'total_purchases' => $query->sum('total_amount'),
            'count' => $query->count(),
        ];

        return view('reports.purchase', compact('purchases', 'summary', 'branches', 'branchId'));
    }

    public function expenseReport(Request $request)
    {
        $user = Auth::user();
        $branchId = $request->get('branch_id');
        $branches = $user->isAdmin() ? Branch::where('is_active', true)->get() : $user->getAccessibleBranches();

        if ($branchId && !$user->isAdmin() && !$user->canAccessBranch($branchId)) {
            return redirect()->route('reports.index')->with('error', 'Unauthorized access');
        }

        $query = Expense::with('user', 'branch');

        if ($branchId) {
            $query->where('branch_id', $branchId);
        } elseif (!$user->isAdmin()) {
            $query->whereIn('branch_id', $user->getAccessibleBranches()->pluck('id'));
        }

        if ($request->has('start_date') && $request->get('start_date')) {
            $query->whereDate('date', '>=', $request->get('start_date'));
        }

        if ($request->has('end_date') && $request->get('end_date')) {
            $query->whereDate('date', '<=', $request->get('end_date'));
        }

        if ($request->has('expense_type') && $request->get('expense_type')) {
            $query->where('expense_type', $request->get('expense_type'));
        }

        $expenses = $query->orderBy('date', 'desc')->paginate(50);

        $summary = [
            'total_expenses' => $query->sum('amount'),
            'count' => $query->count(),
        ];

        return view('reports.expense', compact('expenses', 'summary', 'branches', 'branchId'));
    }

    public function branchComparison(Request $request)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return redirect()->route('reports.index')->with('error', 'Unauthorized access');
        }

        $startDate = $request->get('start_date') ?? now()->startOfMonth();
        $endDate = $request->get('end_date') ?? now()->endOfMonth();

        $branches = Branch::where('is_active', true)->get();

        $branchData = $branches->map(function ($branch) use ($startDate, $endDate) {
            return [
                'branch' => $branch,
                'sales' => Sale::where('branch_id', $branch->id)
                    ->whereBetween('sale_date', [$startDate, $endDate])
                    ->sum('total_amount'),
                'purchases' => Purchase::where('branch_id', $branch->id)
                    ->whereBetween('purchase_date', [$startDate, $endDate])
                    ->sum('total_amount'),
                'expenses' => Expense::where('branch_id', $branch->id)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->sum('amount'),
            ];
        });

        return view('reports.branch-comparison', compact('branchData', 'startDate', 'endDate'));
    }
}
