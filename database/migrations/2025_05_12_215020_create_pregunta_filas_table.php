<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pregunta_filas', function (Blueprint $table) {
            $table->id();
            $table->string('pregunta', 50);
            $table->integer('orden');
            $table->unsignedBigInteger('pregunta_id');
            $table->unsignedBigInteger('columna_id');
            $table->foreign('columna_id')
                ->references('id')
                ->on('pregunta_columnas')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreign('pregunta_id')
                ->references('id')
                ->on('preguntas')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pregunta_filas');
    }
};
