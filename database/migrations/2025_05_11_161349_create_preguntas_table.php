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
        Schema::create('preguntas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 50);
            $table->enum('tipo', ['numerico','texto','opcional','porcentaje','tabla']);
            $table->string('obligatorio', 10);

            $table->unsignedBigInteger('encuesta_id');
            $table->foreign('encuesta_id')
                ->references('id')
                ->on('encuestas')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preguntas');
    }
};
