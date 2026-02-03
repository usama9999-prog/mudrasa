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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');

            $table->integer('zabt')->nullable();             // 60
            $table->integer('tajweed_lehja')->nullable();    // 20
            $table->integer('tarbiti_nisab')->nullable();    // 20
            $table->integer('guzashta_jaiza')->nullable();   // 10
            $table->integer('hazri')->nullable();            // 10
            $table->integer('tarjuma')->nullable();          // 30 
            $table->integer('percentage')->nullable();
            $table->integer('total')->nullable();            // total score

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
