<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_RECEIVED = 'received';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_RETURNED = 'returned';

    protected $fillable = ['branch_id', 'supplier_id', 'invoice_no', 'expense', 'payment_type', 'purchase_date', 'total_amount', 'paid_amount', 'status', 'created_by'];

    protected $casts = [
        'purchase_date' => 'datetime',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function paymentDetails()
    {
        return $this->hasMany(PurchasePaymentDetail::class);
    }

    public function payableAmount(): float
    {
        return round((float) $this->total_amount + (float) $this->expense, 2);
    }

    public function balanceAmount(): float
    {
        return round(max(0, $this->payableAmount() - (float) $this->paid_amount), 2);
    }

    public function isCleared(): bool
    {
        return $this->balanceAmount() <= 0;
    }

    public function paymentLabel(): string
    {
        if (in_array($this->status, [self::STATUS_CANCELLED, self::STATUS_RETURNED], true)) {
            return ucfirst($this->status);
        }

        if ($this->isCleared()) {
            return 'Paid';
        }

        if ($this->payment_type === 'credit') {
            return 'Credit';
        }

        return ucfirst((string) $this->status);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function scopeByBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('purchase_date', [$startDate, $endDate]);
    }
}
