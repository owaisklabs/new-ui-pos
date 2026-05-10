<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku', 'bar_code', 'title', 'subtitle', 'publisher_id', 'published_at',
        'description', 'cost_price', 'sell_price', 'cover_image', 'pages', 'language'
    ];
    public function getCoverImageUrlAttribute()
    {
        return asset('storage/' . $this->cover_image);
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }


    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }


    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }


    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }


    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
    public function priceHistories()
    {
        return $this->hasMany(PriceHistory::class);
    }
}
