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
        Schema::create('etudiants', function (Blueprint $table) {
            $table->id();
            $table->string('CIN');
            $table->string('nom');
            $table->string('prenom');
            $table->date('dateNaissance');
            $table->string('email');
            $table->string('password');
            $table->string('numTele');  
            $table->foreignId('groupe_id')->constrained()
            ->onUpdate('Cascade')
            ->onDelete('Cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etudiants');
    }
};
