<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('books')) {
                Schema::create('books', function (Blueprint $table) {
                $table->id();
                $table->string('sku', 64)->unique()->nullable();
                $table->string('bar_code', 20)->nullable()->index('bar_code');
                $table->string('title', 255)->index('idx_books_title');
                $table->string('subtitle', 255)->nullable();
                $table->foreignId('publisher_id')->nullable()->constrained('publishers')->nullOnDelete();
                $table->date('published_at')->nullable();
                $table->text('description')->nullable();
                $table->decimal('cost_price', 12, 2)->nullable();
                $table->decimal('sell_price', 12, 2)->nullable();
                $table->string('cover_image', 255)->nullable();
                $table->integer('pages')->nullable();
                $table->string('language', 64)->nullable();
                $table->timestamps();
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
        Schema::dropIfExists('books');
    }
}
