<?php

namespace App\Services\Purchaser;

use App\Models\Purchaser;
use Illuminate\Http\Request;

class PurchaserService
{
    public function dataTable(){
        $rows= Purchaser::all();
        return $rows;
    }
    public function store(Request $request){
        Purchaser::create($request->all());
    }
    public function getById($id){
        $row= Purchaser::find($id);
        return $row;
    }
    public function update($request,$purchaser) : bool {
        return $purchaser->update($request->all());
    }
}
