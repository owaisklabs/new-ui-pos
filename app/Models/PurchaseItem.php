<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;
    protected $fillable = ['purchase_id', 'book_id', 'quantity', 'unit_cost', 'line_total',
        'purchase_qty', 'purchase_unit', 'conversion_factor', 'purchase_unit_cost'];

    public function isDualUnit(): bool
    {
        return $this->conversion_factor !== null;
    }

    public function quantityLabel(): string
    {
        return $this->isDualUnit()
            ? $this->purchase_qty . ' ' . ($this->purchase_unit ?: 'Roll') . ($this->purchase_qty == 1 ? '' : 's')
                . ' (' . $this->quantity . ' Feet)'
            : (string) $this->quantity;
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }


    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function returnItems()
    {
        return $this->hasMany(PurchaseReturnItem::class);
    }

    public function returnedQuantity(): int
    {
        return (int) $this->returnItems()->sum('quantity');
    }

    public function returnableQuantity(): int
    {
        return max(0, $this->quantity - $this->returnedQuantity());
    }
}
