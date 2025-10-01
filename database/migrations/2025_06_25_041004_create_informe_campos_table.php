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
        Schema::create('informe_campos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('informe_id')->constrained('informes')->onDelete('cascade');
            $table->foreignId('pregunta_id')->constrained('preguntas')->onDelete('cascade');
            $table->foreignId('fila_id')->nullable()->constrained('pregunta_filas')->onDelete('cascade');
            $table->foreignId('columna_id')->nullable()->constrained('pregunta_columnas')->onDelete('cascade');
            $table->enum('tipo_analisis', ['conteo', 'suma', 'promedio'])->nullable(); // Tipo de análisis: conteo, suma o promedio
            $table->string('titulo')->nullable(); // Título personalizado
            $table->unsignedInteger('orden')->default(0);
            $table->enum('estado', ['activo', 'inactivo'])->default('inactivo');
            $table->timestamp('deleted_at')->nullable();                   
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informe_campos');
    }
};
