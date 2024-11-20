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
        Schema::create('miembros_familia', function (Blueprint $table) {
            $table->id();
            $table->integer('mujeres');
            $table->integer('hombres');
            $table->integer('total_integrantes');
            $table->unsignedBigInteger('afiliado_id');
            $table->timestamps();

            $table->foreign('afiliado_id')->references('id')->on('afiliados')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('miembros_familia');
    }
};
