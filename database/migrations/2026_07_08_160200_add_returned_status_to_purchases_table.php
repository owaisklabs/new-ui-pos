<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddReturnedStatusToPurchasesTable extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE purchases MODIFY COLUMN status ENUM('pending', 'received', 'cancelled', 'returned') DEFAULT 'pending'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE purchases MODIFY COLUMN status ENUM('pending', 'received', 'cancelled') DEFAULT 'pending'");
    }
}
