<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('price_histories')) {
             Schema::create('price_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('book_id')->constrained('books')->cascadeOnDelete();
                $table->decimal('old_price', 12, 2)->nullable();
                $table->decimal('new_price', 12, 2)->nullable();
                $table->timestamp('changed_at')->useCurrent();
                $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->string('note', 255)->nullable();
                $table->index('book_id', 'idx_price_hist_book');
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
        Schema::dropIfExists('price_histories');
    }
}
