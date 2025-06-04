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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->double('limit_paylater');
            $table->double('limit_paylater_used');
            $table->double('limit_loan');
            $table->double('limit_loan_used');
            $table->double('limit_credit');
            $table->double('limit_credit_used');
            $table->double('point');
            $table->double('balance')->default(0);
            $table->timestamp('last_balance_in')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
