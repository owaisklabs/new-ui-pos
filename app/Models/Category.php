<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public const MODE_FEET_ROLL = 'feet_roll';
    public const MODE_PIECES = 'pieces';

    protected $fillable = ['name', 'slug', 'unit_mode', 'parent_id'];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function isFeetRoll(): bool
    {
        return $this->unit_mode === self::MODE_FEET_ROLL;
    }

    public function isPieces(): bool
    {
        return $this->unit_mode === self::MODE_PIECES;
    }

    public function isSystem(): bool
    {
        return in_array($this->slug, ['feet-roll', 'pieces'], true);
    }

    public function unitModeLabel(): string
    {
        return $this->isPieces() ? 'Pieces' : 'Feet / Roll';
    }
}
