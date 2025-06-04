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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts')->cascadeOnDelete();
            $table->foreignId('approval_id')->constrained('approvals')->cascadeOnDelete();
            $table->double('amount');
            $table->integer('tenor');
            $table->integer('paid_tenor');
            $table->double('instalment');
            $table->string('description');
            $table->text('status');
            $table->boolean('is_paid_off')->default(false);
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_approved_admin')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
