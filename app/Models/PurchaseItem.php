<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;
    protected $fillable = ['purchase_id', 'book_id', 'quantity', 'unit_cost', 'line_total'];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }


    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
