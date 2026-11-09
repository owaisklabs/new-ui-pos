<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasePaymentDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'amount_received',
        'payment_type',
        'remarks',
        'account_number',
        'cheque_number',
        'created_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}
