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
        Schema::create('user_pago', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_pago');

            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');

            $table->foreign('id_pago')->references('id')->on('pagos')->onDelete('restrict')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_pago');
    }
};
