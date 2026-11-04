<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBranchRolesTable extends Migration
{
    public function up()
    {
        Schema::create('user_branch_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->cascadeOnDelete();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Composite unique constraint
            $table->unique(['user_id', 'branch_id', 'role_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_branch_roles');
    }
}
