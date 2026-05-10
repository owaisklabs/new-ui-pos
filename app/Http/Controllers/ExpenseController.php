<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::orderBY('date','desc');
        if ($request->has('query')) {
            $filters = $request->query('query');

            if (!empty($filters['from_date'])) {
                $query->whereDate('date', '>=', $filters['from_date']);
            }

            if (!empty($filters['to_date'])) {
                $query->whereDate('date', '<=', $filters['to_date']);
            }
        }
        $expenses = $query->paginate(50);
        return view('expense.index', compact('expenses'));

    }

    public function create()
    {
        return view('expense.create');
    }

    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required',
            'expense_type' => 'required',
            'amount' => 'required',
            'date' => 'required|date',
        ]);
        Expense::create([
            'title' => $request->title,
            'expense_type' => $request->expense_type,
            'amount' => $request->amount,
            'date' => $request->date,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('expenses.index')->with('success', 'Expense added');

    }

    public function edit(Expense $expense)
    {
        return view('expense.update', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'title' => 'required',
            'expense_type' => 'required',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ]);

        $expense->update($request->all());

        return redirect()->route('expenses.index')->with('success', 'Expense updated');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted');
    }

}
