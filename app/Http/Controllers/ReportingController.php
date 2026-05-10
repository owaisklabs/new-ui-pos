<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportingController extends Controller
{
    public function reports(Request $request){
        $from = $request->filled('from')
            ? Carbon::parse($request->from)->startOfDay()
            : now()->startOfMonth();

        $to = $request->filled('to')
            ? Carbon::parse($request->to)->endOfDay()
            : now()->endOfMonth();
        $totalSales = Sale::whereBetween('sale_date', [$from, $to])
            ->sum('total_amount');

        $totalCost = SaleItem::whereHas('sale', function ($q) use ($from, $to) {
            $q->whereBetween('sale_date', [$from, $to]);
        })
            ->selectRaw('SUM(quantity * cost_price) as total')
            ->value('total') ?? 0;

        $totalExpenses = Expense::whereBetween('date', [$from, $to])
            ->sum('amount');

        $profitLoss = $totalSales - $totalCost - $totalExpenses;
        
        return view('report.index', [
            'totalSales' => $totalSales ?? 0,
            'totalCost' => $totalCost,
            'totalExpenses' => $totalExpenses ?? 0,
            'profitLoss' => $profitLoss
        ]);
    }
}
