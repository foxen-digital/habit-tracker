<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('walk_entries', function (Blueprint $table) {
            $table->id();
            $table->decimal('distance_miles', 4, 2);
            $table->integer('steps')->nullable();
            $table->date('date')->unique();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('walk_entries');
    }
};
