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
        Schema::create('animals', function (Blueprint $table) {
            $table->id();
            $table->string('animal_no')->unique(); // unique animal number
            $table->string('type'); // e.g., cow, goat
            $table->integer('purchase_price');
            $table->integer('fodder_cost')->default(0);
            $table->integer('transportation_cost')->default(0);
            $table->integer('butcher_cost')->default(0);
            $table->integer('writing_cost')->default(0);
            $table->integer('miscellaneous_cost')->default(0);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};
