<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    const ADMIN = 'admin';
    const BRANCH_ADMIN = 'branch_admin';
    const BRANCH_USER = 'branch_user';
    const CASHIER = 'cashier';

    public function userBranchRoles()
    {
        return $this->hasMany(UserBranchRole::class);
    }
}
