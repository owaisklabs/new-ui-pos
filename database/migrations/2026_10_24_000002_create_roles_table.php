<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateRolesTable extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Insert default roles
        DB::table('roles')->insert([
            ['name' => 'admin', 'description' => 'System Administrator', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'branch_admin', 'description' => 'Branch Administrator', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'branch_user', 'description' => 'Branch User', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'cashier', 'description' => 'Cashier', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
