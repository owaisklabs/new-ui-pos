<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          if (!Schema::hasTable('purchase_items')) {
            Schema::create('purchase_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('purchase_id')
                    ->constrained('purchases')
                    ->cascadeOnDelete(); // ON DELETE CASCADE
                $table->foreignId('book_id')
                    ->constrained('books')
                    ->restrictOnDelete(); // ON DELETE RESTRICT
                $table->integer('quantity');
                $table->decimal('unit_cost', 12, 2);
                $table->decimal('line_total', 14, 2);
                $table->timestamps();
                $table->index('purchase_id', 'idx_purchase_items_purchase');
            });
          }
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_items');
    }
}
