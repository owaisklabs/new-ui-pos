<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBranchIdToInventoriesTable extends Migration
{
    public function up()
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->after('id')->constrained('branches')->nullOnDelete();
            $table->unique(['book_id', 'branch_id']);
            $table->index('branch_id');
        });
    }

    public function down()
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropUniqueIfExists(['book_id', 'branch_id']);
            $table->dropForeignKeyIfExists(['branch_id']);
            $table->dropColumn('branch_id');
        });
    }
}
