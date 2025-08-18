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
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->unique()->constrained('students')->cascadeOnDelete();

            // Identificación básica
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();       // segundo apellido / middle
            $table->date('birth_date')->nullable();
            $table->enum('sex', ['M','F','X'])->nullable();
            $table->string('curp', 18)->nullable()->index(); // México

            // Contacto y domicilio
            $table->string('phone')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();

            // Salud
            $table->enum('blood_type', ['A+','A-','B+','B-','AB+','AB-','O+','O-'])->nullable();
            $table->json('allergies')->nullable();           // lista
            $table->json('chronic_diseases')->nullable();    // lista
            $table->json('medications')->nullable();         // lista
            $table->boolean('has_disability')->default(false);
            $table->text('medical_notes')->nullable();

            // Contacto de emergencia
            $table->string('emergency_name')->nullable();
            $table->string('emergency_relation')->nullable();
            $table->string('emergency_phone')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};
