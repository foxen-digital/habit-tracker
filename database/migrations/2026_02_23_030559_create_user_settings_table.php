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
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Goal settings
            $table->decimal('weight_goal_kg', 5, 2)->default(25);
            $table->decimal('daily_walk_target_miles', 4, 2)->default(3.0);
            $table->integer('daily_water_target_glasses')->default(8);

            // Preferences
            $table->enum('weight_unit', ['kg', 'lbs'])->default('kg');
            $table->enum('distance_unit', ['miles', 'km'])->default('miles');

            $table->timestamps();

            $table->unique('user_id'); // One settings row per user
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};
