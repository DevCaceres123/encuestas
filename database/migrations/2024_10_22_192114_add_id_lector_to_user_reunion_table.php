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
        Schema::table('user_reunion', function (Blueprint $table) {
            $table->unsignedBigInteger('id_lector');
            $table->foreign('id_lector')->references('id')->on('lectores')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_reunion', function (Blueprint $table) {
            $table->dropForeign('user_reunion_id_lector_foreign');
            $table->dropColumn('id_lector');
        });
    }
};
