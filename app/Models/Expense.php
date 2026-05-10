<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;protected $fillable = [
        'title',
        'expense_type',
        'amount',
        'date',
        'created_by'
    ];

}
