<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDualUnitSupport extends Migration
{
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            if (!Schema::hasColumn('books', 'conversion_factor')) {
                $table->decimal('conversion_factor', 10, 2)->nullable();
                $table->string('purchase_unit', 32)->nullable();
                $table->string('base_unit', 32)->nullable();
            }
        });

        Schema::table('purchase_items', function (Blueprint $table) {
            if (!Schema::hasColumn('purchase_items', 'purchase_qty')) {
                $table->unsignedInteger('purchase_qty')->nullable();
                $table->string('purchase_unit', 32)->nullable();
                $table->decimal('conversion_factor', 10, 2)->nullable();
                $table->decimal('purchase_unit_cost', 12, 2)->nullable();
            }
        });

        Schema::table('sale_items', function (Blueprint $table) {
            if (!Schema::hasColumn('sale_items', 'roll_qty')) {
                $table->unsignedInteger('roll_qty')->nullable();
                $table->unsignedInteger('feet_qty')->nullable();
                $table->decimal('conversion_factor', 10, 2)->nullable();
                $table->string('purchase_unit', 32)->nullable();
                $table->string('base_unit', 32)->nullable();
            }
        });

        if (Schema::hasTable('user_cart') && !Schema::hasColumn('user_cart', 'roll_qty')) {
            Schema::table('user_cart', function (Blueprint $table) {
                $table->unsignedInteger('roll_qty')->default(0);
                $table->unsignedInteger('feet_qty')->default(0);
            });
        }
    }

    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['conversion_factor', 'purchase_unit', 'base_unit']);
        });
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->dropColumn(['purchase_qty', 'purchase_unit', 'conversion_factor', 'purchase_unit_cost']);
        });
        Schema::table('sale_items', function (Blueprint $table) {
            $table->dropColumn(['roll_qty', 'feet_qty', 'conversion_factor', 'purchase_unit', 'base_unit']);
        });
        if (Schema::hasTable('user_cart')) {
            Schema::table('user_cart', function (Blueprint $table) {
                $table->dropColumn(['roll_qty', 'feet_qty']);
            });
        }
    }
}
