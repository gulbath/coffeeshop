<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaction_items', function (Blueprint $table) {
            if (!Schema::hasColumn('transaction_items', 'product_name')) {
                $table->string('product_name', 255)->nullable()->after('product_id');
            }
            if (!Schema::hasColumn('transaction_items', 'product_name_snapshot')) {
                $table->string('product_name_snapshot', 255)->nullable()->after('product_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transaction_items', function (Blueprint $table) {
            if (Schema::hasColumn('transaction_items', 'product_name')) {
                $table->dropColumn('product_name');
            }
            if (Schema::hasColumn('transaction_items', 'product_name_snapshot')) {
                $table->dropColumn('product_name_snapshot');
            }
        });
    }
};