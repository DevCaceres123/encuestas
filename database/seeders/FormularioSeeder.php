<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Formulario;


class FormularioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $encuesta = new Formulario();
        $encuesta->titulo_formulario = '1 formulario de prueba';
        $encuesta->descripcion_formulario = 'Formulario solo de prueba para ver los datos e informacion';
        $encuesta->estado = 'proceso';
        $encuesta->user_id = 1;
        $encuesta->encuesta_id = 1;
        $encuesta->save();
    }
}
