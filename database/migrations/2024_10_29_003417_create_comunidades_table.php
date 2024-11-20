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
        Schema::create('comunidades', function (Blueprint $table) {
            $table->id();
            $table->string('titulo',100);
            $table->text('descripcion',200);
            $table->unsignedBigInteger('distrito_id');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();

            $table->foreign('distrito_id')
            ->references('id')
            ->on('distritos')
            ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comunidad');
    }
};
