<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = ['branch_id', 'book_id', 'quantity', 'location', 'reorder_level'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function scopeByBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('quantity <= reorder_level');
    }
}
