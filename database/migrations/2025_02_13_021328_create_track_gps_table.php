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
        Schema::create('track_gps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_task_id')->constrained('driver_tasks')->cascadeOnDelete();
            $table->double('latitude', 15, 8);
            $table->double('longitude', 15, 8);
            $table->timestamp('tracked_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('track_gps');
    }
};
