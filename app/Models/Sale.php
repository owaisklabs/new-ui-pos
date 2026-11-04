<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = ['branch_id', 'customer_id', 'invoice_no', 'sale_date', 'total_amount', 'paid_amount', 'status', 'created_by'];

    protected $casts = [
        'sale_date' => 'datetime',
    ];

    const STATUS_PAID = 'paid';
    const STATUS_OPEN = 'open';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REFUND = 'refunded';

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

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function salesPaymentsDetails()
    {
        return $this->hasMany(SalesPaymentDetail::class, 'sales_id');
    }

    public function scopeByBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('sale_date', [$startDate, $endDate]);
    }
}
