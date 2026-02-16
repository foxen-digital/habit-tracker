<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('water_entries', function (Blueprint $table) {
            $table->id();
            $table->integer('glasses'); // 1 glass = 250ml
            $table->date('date')->unique();
            $table->timestamps();
            
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('water_entries');
    }
};
