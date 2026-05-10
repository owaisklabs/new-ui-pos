<?php

namespace App\Services\Item;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemService
{
    public function dataTable(){
        $rows= Item::all();
        return $rows;
    }
    public function store(Request $request){
        Item::create($request->all());
    }
    public function getById($id){
        $row= Item::find($id);
        return $row;
    }
    public function update($request,$purchaser) : bool {
        return $purchaser->update($request->all());
    }
}
