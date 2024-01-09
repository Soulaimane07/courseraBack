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
        Schema::create('cours', function (Blueprint $table) {
            $table->id();
            $table->string('lien');
            $table->string('titre');
            $table->string('desc');
            $table->date('dateDebut');
            $table->date('dateFin');
            $table->date('deadline_control');
            $table->foreignId('module_id')->constrained()
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
        Schema::dropIfExists('cours');
    }
};
