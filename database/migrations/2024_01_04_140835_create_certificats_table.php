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
        Schema::create('certificats', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->foreignId('etudiant_id')->constrained()->onUpdate('Cascade')->onDelete('Cascade');
            $table->foreignId('cour_id')->constrained()->onUpdate('cascade')->onDelete('Cascade');
            $table->string('pdf')->nullable();
            $table->date ('date_obtention');
            $table->float('note');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificats');
    }
};
