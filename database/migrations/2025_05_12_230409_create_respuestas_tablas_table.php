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
        Schema::create('respuestas_tablas', function (Blueprint $table) {
            $table->id();
            $table->string('respuesta', 50);
            $table->unsignedBigInteger('pregunta_id');
            $table->unsignedBigInteger('afiliado_id');
            $table->unsignedBigInteger('formulario_id');
            $table->unsignedBigInteger('encuesta_id');
            $table->unsignedBigInteger('pregunta_fila_id');
            $table->unsignedBigInteger('pregunta_columna_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('pregunta_opciones_columna_id')->nullable();

            $table->foreign('pregunta_id')
                ->references('id')
                ->on('preguntas')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            
            $table->foreign('afiliado_id')
                ->references('id')
                ->on('afiliados')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            
            $table->foreign('formulario_id')
                ->references('id')
                ->on('formularios')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            
            $table->foreign('encuesta_id')
                ->references('id')
                ->on('encuestas')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('pregunta_fila_id')
                ->references('id')
                ->on('pregunta_filas')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            
            $table->foreign('pregunta_columna_id')
                ->references('id')
                ->on('pregunta_columnas')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('pregunta_opciones_columna_id')
                ->references('id')
                ->on('pregunta_opciones_columnas')
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
        Schema::dropIfExists('respuestas_tablas');
    }
};
