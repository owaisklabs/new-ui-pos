<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_title',
        'email',
        'mobile_number',
        'address',
        'logo',
        'invoice_type',
        'tax_included',
    ];

    protected $casts = [
        'tax_included' => 'boolean',
    ];

    public function logoUrl(): ?string
    {
        if (!$this->logo) {
            return null;
        }

        if (str_starts_with($this->logo, 'http://') || str_starts_with($this->logo, 'https://')) {
            return $this->logo;
        }

        return Storage::url($this->logo);
    }

    public function isThermalInvoice(): bool
    {
        return $this->invoice_type === 'thermal';
    }
}
