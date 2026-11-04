<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'address',
        'phone',
        'email',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_branch_roles')
            ->withPivot('role_id', 'is_active')
            ->withTimestamps();
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function userBranchRoles()
    {
        return $this->hasMany(UserBranchRole::class);
    }
}
