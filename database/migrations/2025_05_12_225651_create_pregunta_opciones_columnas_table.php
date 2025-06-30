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
        Schema::create('pregunta_opciones_columnas', function (Blueprint $table) {
            $table->id();
            $table->string('opcion', 50);
            $table->unsignedBigInteger('columna_id');
            $table->foreign('columna_id')
                ->references('id')
                ->on('pregunta_columnas')
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
        Schema::dropIfExists('pregunta_opciones_columnas');
    }
};
