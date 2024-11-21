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
        Schema::create('afiliados', function (Blueprint $table) {
            $table->id();
            $table->string('nombres');
            $table->string('paterno');
            $table->string('materno');
            $table->string('ci')->unique();
            $table->date('fecha_nacimiento')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('comunidad_id');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('comunidad_id')->references('id')->on('comunidades')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('afiliado');
    }
};
