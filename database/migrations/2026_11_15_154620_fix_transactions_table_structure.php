<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'subtotal')) {
                $table->decimal('subtotal', 12, 2)->default(0)->after('user_id');
            }
            if (!Schema::hasColumn('transactions', 'discount_amount')) {
                $table->decimal('discount_amount', 12, 2)->default(0)->after('subtotal');
            }
            if (!Schema::hasColumn('transactions', 'cash_amount')) {
                $table->decimal('cash_amount', 12, 2)->default(0)->after('total_amount');
            }
            if (!Schema::hasColumn('transactions', 'change_amount')) {
                $table->decimal('change_amount', 12, 2)->default(0)->after('cash_amount');
            }
            if (!Schema::hasColumn('transactions', 'payment_method')) {
                $table->enum('payment_method', ['cash', 'qris', 'debit'])->default('cash')->after('change_amount');
            }
            if (!Schema::hasColumn('transactions', 'payment_status')) {
                $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('paid')->after('payment_method');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'discount_amount', 'cash_amount', 'change_amount', 'payment_method', 'payment_status']);
        });
    }
};