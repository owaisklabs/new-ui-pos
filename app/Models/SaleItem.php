<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasFactory;
    protected $fillable = ['sale_id', 'book_id', 'quantity','unit_price','cost_price','sale_price','line_total','discount','total',
        'roll_qty','feet_qty','roll_price','feet_price','conversion_factor','purchase_unit','base_unit'];

    /** Saved roll price; legacy rows (no roll_price) fall back to per-base-unit price * factor. */
    public function rollPriceValue(): float
    {
        if ($this->roll_price !== null) {
            return (float) $this->roll_price;
        }
        return round((float) $this->unit_price * (float) $this->conversion_factor, 2);
    }

    public function feetPriceValue(): float
    {
        return $this->feet_price !== null ? (float) $this->feet_price : (float) $this->unit_price;
    }

    /** Unit price text for invoices/reports. */
    public function priceLabel(): string
    {
        if (!$this->isDualUnit()) {
            return number_format($this->unit_price, 2);
        }
        $parts = [];
        if ((int) $this->roll_qty > 0) {
            $parts[] = number_format($this->rollPriceValue(), 2) . ' / ' . ($this->purchase_unit ?: 'Roll');
        }
        if ((int) $this->feet_qty > 0 || empty($parts)) {
            $parts[] = number_format($this->feetPriceValue(), 2) . ' / ' . ($this->base_unit ?: 'Feet');
        }
        return implode(' + ', $parts);
    }

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
