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
        Schema::create('daily_goal_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_goal_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->boolean('completed')->default(false);
            $table->timestamps();

            $table->unique(['daily_goal_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_goal_completions');
    }
};
