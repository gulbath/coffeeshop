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
        Schema::table('expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('expenses', 'expense_number')) {
                $table->string('expense_number', 50)->nullable()->unique();
            }
            if (!Schema::hasColumn('expenses', 'category')) {
                $table->string('category', 100)->nullable();
            }
            if (!Schema::hasColumn('expenses', 'amount')) {
                $table->decimal('amount', 12, 2)->default(0);
            }
            if (!Schema::hasColumn('expenses', 'expense_date')) {
                $table->date('expense_date')->nullable();
            }
            if (!Schema::hasColumn('expenses', 'notes')) {
                $table->string('notes', 255)->nullable();
            }
            if (!Schema::hasColumn('expenses', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn(['expense_number', 'category', 'amount', 'expense_date', 'notes', 'user_id']);
        });
    }
};