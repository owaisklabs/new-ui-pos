<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleReturnItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'sale_return_id',
        'sale_item_id',
        'book_id',
        'quantity',
        'unit_price',
        'total_price'
    ];
    public function saleReturn()
    {
        return $this->belongsTo(SaleReturn::class);
    }

    public function saleItem()
    {
        return $this->belongsTo(SaleItem::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
