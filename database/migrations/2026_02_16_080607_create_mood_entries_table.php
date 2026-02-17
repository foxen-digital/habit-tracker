<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mood_entries', function (Blueprint $table) {
            $table->id();
            $table->enum('mood', ['great', 'good', 'okay', 'bad', 'terrible']);
            $table->integer('energy_level')->default(5); // 1-10
            $table->integer('sleep_quality')->nullable(); // 1-10
            $table->text('notes')->nullable();
            $table->date('date')->unique();
            $table->timestamps();

            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mood_entries');
    }
};
