<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceHistory extends Model
{
    use HasFactory;

    protected $fillable = ['book_id', 'old_price', 'new_price', 'changed_at', 'changed_by', 'note'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
