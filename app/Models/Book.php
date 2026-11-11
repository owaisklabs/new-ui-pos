<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id', 'sku', 'bar_code', 'title', 'category_id', 'subtitle', 'publisher_id', 'published_at',
        'description', 'cost_price', 'sell_price', 'cover_image', 'pages', 'language',
        'conversion_factor', 'purchase_unit', 'base_unit'
    ];

    public function isDualUnit(): bool
    {
        return !$this->isPieces()
            && $this->conversion_factor !== null
            && (float) $this->conversion_factor > 1;
    }

    public function isPieces(): bool
    {
        if ($this->base_unit === 'Pieces') {
            return true;
        }

        if ($this->base_unit !== null && $this->base_unit !== '') {
            return false;
        }

        if (!$this->category_id) {
            return false;
        }

        return (bool) optional($this->category)->isPieces();
    }

    public function priceLabel($price): string
    {
        if ($this->isPieces()) {
            return number_format($price, 2) . ' / Piece';
        }

        if (!$this->isDualUnit()) {
            return (string) $price;
        }

        return number_format($price, 2) . ' / ' . ($this->base_unit ?: 'Feet')
            . ' (' . number_format($price * (float) $this->conversion_factor, 2) . ' / ' . ($this->purchase_unit ?: 'Roll') . ')';
    }

    public function stockLabel($quantity): string
    {
        if ($this->isPieces()) {
            $qty = (float) $quantity;
            $shown = ((int) $qty == $qty) ? (string) (int) $qty : (string) $qty;

            return $shown . ($qty == 1.0 ? ' Piece' : ' Pieces');
        }

        if (!$this->isDualUnit()) {
            return (string) $quantity;
        }

        $base = $this->base_unit ?: 'Feet';

        $factor = (float) $this->conversion_factor;
        $packs = $quantity > 0 ? (int) floor($quantity / $factor) : 0;

        if ($packs === 0) {
            return $quantity . ' ' . $base;
        }

        $loose = $quantity - ($packs * $factor);

        return $quantity . ' ' . $base . ' (' . \App\Services\UnitConversion::format($packs, $loose, $this->purchase_unit, $this->base_unit) . ')';
    }

    public function getCoverImageUrlAttribute()
    {
        return asset('storage/' . $this->cover_image);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function priceHistories()
    {
        return $this->hasMany(PriceHistory::class);
    }

    public function scopeByBranch($query, $branchId)
    {
        return $query->where(function ($q) use ($branchId) {
            $q->where('branch_id', $branchId)
              ->orWhereNull('branch_id');
        });
    }
}
