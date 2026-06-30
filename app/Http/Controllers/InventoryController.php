<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $inventories = Inventory::with('book');
        if ($request->has('query')) {
            $filters = $request->query('query');
            if (!empty($filters['name'])) {
                $inventories->whereHas('book', function ($query) use ($filters) {
                    $query->where('title', 'LIKE', '%' . $filters['name'] . '%');
                });
            }
            if (!empty($filters['from_date'])) {
                $inventories->whereHas('book', function ($query) use ($filters) {
                    $query->whereDate('created_at', '>=', $filters['from_date']);
                });
            }
            if (!empty($filters['to_date'])) {
                $inventories->whereHas('book', function ($query) use ($filters) {
                    $query->whereDate('created_at', '<=', $filters['to_date']);
                });
            }
        }
        $inventories = $inventories->paginate(50);
        return view('inventory.index',compact('inventories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function show(Inventory $inventory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function edit(Inventory $inventory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Inventory $inventory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inventory $inventory)
    {
        //
    }
}
