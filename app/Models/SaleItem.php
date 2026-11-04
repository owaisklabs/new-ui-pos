<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasFactory;
    protected $fillable = ['sale_id', 'book_id', 'quantity','unit_price','cost_price','sale_price','line_total','discount','total',
        'roll_qty','feet_qty','conversion_factor','purchase_unit','base_unit'];

    public function isDualUnit(): bool
    {
        return $this->conversion_factor !== null;
    }

    public function quantityLabel(): string
    {
        return $this->isDualUnit()
            ? \App\Services\UnitConversion::format($this->roll_qty, $this->feet_qty, $this->purchase_unit, $this->base_unit)
            : (string) $this->quantity;
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
    public function returnItems()
    {
        return $this->hasMany(SaleReturnItem::class);
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
