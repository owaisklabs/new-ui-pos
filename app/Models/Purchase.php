<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $fillable = ['supplier_id', 'invoice_no','expense', 'payment_type','purchase_date', 'total_amount', 'status', 'created_by'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }


    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
