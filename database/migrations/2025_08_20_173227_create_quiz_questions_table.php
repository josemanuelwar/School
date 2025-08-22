<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['single_choice', 'multiple_choice', 'true_false', 'short_text', 'long_text', 'numeric']);
            $table->text('prompt');
            $table->json('meta')->nullable();     // imágenes, puntaje por pregunta, etc.
            $table->unsignedSmallInteger('order')->default(1);
            $table->unsignedSmallInteger('points')->default(1);
            $table->timestamps();
            $table->unique(['quiz_id','order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};
