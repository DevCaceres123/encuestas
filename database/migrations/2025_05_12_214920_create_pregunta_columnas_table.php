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
        Schema::create('pregunta_columnas', function (Blueprint $table) {
            $table->id();
            $table->string('pregunta', 100);
            $table->enum('tipo', ['numero','texto','opcion','porcentaje','pregunta']);
            // $table->string('sumar', 10)->nullable();
            // $table->integer('total')->nullable();
            $table->integer('orden');
            $table->unsignedBigInteger('pregunta_id');
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
        Schema::dropIfExists('pregunta_columnas');
    }
};
