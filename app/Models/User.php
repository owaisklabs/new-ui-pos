<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'default_branch_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function cart()
    {
        return $this->belongsToMany(Book::class, 'user_cart')->withPivot('quantity', 'discount', 'roll_qty', 'feet_qty', 'roll_price', 'feet_price', 'unit_price');
    }

    public function defaultBranch()
    {
        return $this->belongsTo(Branch::class, 'default_branch_id');
    }

    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'user_branch_roles')
            ->withPivot('role_id', 'is_active')
            ->withTimestamps();
    }

    public function userBranchRoles()
    {
        return $this->hasMany(UserBranchRole::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_branch_roles')
            ->withPivot('branch_id', 'is_active')
            ->withTimestamps();
    }

    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles()->where('name', $role)->exists();
        }
        return $this->roles()->whereIn('name', (array) $role)->exists();
    }

    public function hasRoleInBranch($role, $branchId = null)
    {
        $query = $this->userBranchRoles()
            ->whereHas('role', function ($q) use ($role) {
                if (is_string($role)) {
                    $q->where('name', $role);
                } else {
                    $q->whereIn('name', $role);
                }
            });

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        return $query->exists();
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isBranchAdmin($branchId = null)
    {
        return $this->hasRoleInBranch('branch_admin', $branchId);
    }

    public function canAccessBranch($branchId)
    {
        if ($this->isAdmin()) {
            return true;
        }

        return $this->userBranchRoles()
            ->where('branch_id', $branchId)
            ->where('is_active', true)
            ->exists();
    }

    public function getAccessibleBranches()
    {
        if ($this->isAdmin()) {
            return Branch::where('is_active', true)->get();
        }

        return $this->branches()
            ->where('user_branch_roles.is_active', true)
            ->get();
    }
}
