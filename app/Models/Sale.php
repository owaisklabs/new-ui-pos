<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'invoice_no', 'sale_date', 'total_amount', 'paid_amount', 'status', 'created_by'];
    const STATUS_PAID = 'paid';
    const STATUS_OPEN = 'open';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REFUND = 'refunded';

//    public function customer()
//    {
//        return $this->belongsTo(Customer::class);
//    }


    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }


    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function salesPaymentsDetails()
    {
        return $this->hasMany(SalesPaymentDetail::class, 'sales_id');
    }
}
