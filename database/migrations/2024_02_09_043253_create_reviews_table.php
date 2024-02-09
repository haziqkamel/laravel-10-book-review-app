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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('book_id'); // Foreign Key
            $table->text('review');
            $table->unsignedTinyInteger('rating'); // Rating 1-5
            $table->timestamps();
            // $table->foreign('book_id')->references('id')->on('books')
            //     ->onDelete('cascade'); // Relationship with book table on id
            $table->foreignId('book_id')->constrained()->cascadeOnDelete(); // shorter way
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
