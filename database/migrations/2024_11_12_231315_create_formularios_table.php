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
        Schema::create('formularios', function (Blueprint $table) {
            $table->id();
            $table->string('titulo_formulario', 50);
            $table->string('descripcion_formulario', 100);
            $table->enum('estado', ['proceso', 'terminado'])->default('proceso');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('encuesta_id');
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');

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
        Schema::dropIfExists('formularios');
    }
};
