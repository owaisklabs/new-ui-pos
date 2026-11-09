<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddPaidAmountAndPurchasePayments extends Migration
{
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            if (!Schema::hasColumn('purchases', 'paid_amount')) {
                $table->decimal('paid_amount', 12, 2)->default(0);
            }
        });

        // Existing cash, online, and cheque purchases were already settled.
        DB::table('purchases')
            ->where(function ($query) {
                $query->whereNull('payment_type')
                    ->orWhere('payment_type', '!=', 'credit');
            })
            ->update([
                'paid_amount' => DB::raw('total_amount + COALESCE(expense, 0)'),
            ]);

        if (!Schema::hasTable('purchase_payment_details')) {
            Schema::create('purchase_payment_details', function (Blueprint $table) {
                $table->id();
                $table->foreignId('purchase_id')->constrained('purchases')->cascadeOnDelete();
                $table->decimal('amount_received', 12, 2)->default(0);
                $table->string('payment_type')->nullable();
                $table->string('account_number')->nullable();
                $table->string('cheque_number')->nullable();
                $table->text('remarks')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('purchase_payment_details');

        Schema::table('purchases', function (Blueprint $table) {
            if (Schema::hasColumn('purchases', 'paid_amount')) {
                $table->dropColumn('paid_amount');
            }
        });
    }
}
