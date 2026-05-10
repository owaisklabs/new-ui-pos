<?php

namespace App\Services\Purchase;

use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseService
{
    public function dataTable(){
        $rows= Purchase::all();
        return $rows;
    }
    public function store(Request $request){
        Purchase::create($request->all());
    }
    public function getById($id){
        $row= Purchase::find($id);
        return $row;
    }
    public function update($request,$purchaser) : bool {
        return $purchaser->update($request->all());
    }
}
