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
        Schema::create('quiz_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('quiz_attempts')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('quiz_questions')->cascadeOnDelete();

            // Para choice: guarda IDs de opciones elegidas; para texto: respuesta libre
            $table->json('selected_option_ids')->nullable();
            $table->text('text_answer')->nullable();
            $table->decimal('numeric_answer', 8, 2)->nullable();

            $table->boolean('is_correct')->nullable(); // cálculo automático si aplica
            $table->unsignedSmallInteger('points_awarded')->nullable();
            $table->timestamps();
            
            $table->unique(['attempt_id','question_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_answers');
    }
};
