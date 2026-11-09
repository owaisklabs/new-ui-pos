<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddTransactionPricingToSaleItems extends Migration
{
    public function up()
    {
        // Independent selling prices entered at sale time (never derived from books.sell_price).
        Schema::table('sale_items', function (Blueprint $table) {
            if (!Schema::hasColumn('sale_items', 'roll_price')) {
                $table->decimal('roll_price', 12, 2)->nullable();
                $table->decimal('feet_price', 12, 2)->nullable();
            }
        });

        // Cart pivot holds the in-progress prices until checkout.
        if (Schema::hasTable('user_cart') && !Schema::hasColumn('user_cart', 'roll_price')) {
            Schema::table('user_cart', function (Blueprint $table) {
                $table->decimal('roll_price', 12, 2)->nullable();
                $table->decimal('feet_price', 12, 2)->nullable();
                $table->decimal('unit_price', 12, 2)->nullable();
            });
        }

        // books.sell_price is already nullable (see create_books_table); it is simply no longer
        // used or required. The column is kept so historical data is untouched.
    }

    public function down()
    {
        Schema::table('sale_items', function (Blueprint $table) {
            $table->dropColumn(['roll_price', 'feet_price']);
        });
        if (Schema::hasTable('user_cart')) {
            Schema::table('user_cart', function (Blueprint $table) {
                $table->dropColumn(['roll_price', 'feet_price', 'unit_price']);
            });
        }
    }
}
