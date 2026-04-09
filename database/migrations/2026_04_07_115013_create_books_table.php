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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
    
            $table->foreignId('genre_id')
                ->constrained('genres')
                ->cascadeOnDelete();

            $table->string('title');
            $table->string('author');
            $table->unsignedInteger('pages')->nullable();

            $table->enum('status', ['to_read', 'reading', 'finished'])
                ->default('to_read');

            $table->unsignedTinyInteger('rating')->nullable(); // 1-5

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
