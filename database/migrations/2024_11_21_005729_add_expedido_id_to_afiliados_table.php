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
        Schema::table('afiliados', function (Blueprint $table) {
            $table->unsignedBigInteger('expedido_id')->after('comunidad_id')->nullable();
            $table->foreign('expedido_id')->references('id')->on('expedidos')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('afiliados', function (Blueprint $table) {
            $table->dropForeign('afiliados_expedido_id_foreign');
            $table->dropColumn('departamento_id');
        });
    }
};
