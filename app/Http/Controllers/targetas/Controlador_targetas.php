<?php

namespace App\Http\Controllers\targetas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Controlador_targetas extends Controller
{
    public function obtenerCodigoTargeta(Request $request)
    {
        
        Storage::disk('local')->put('data.txt', $request->dato);
        return "correcto";
    }

    public function leerCodigoTargeta()
    {
        // Asegúrate de que el archivo existe
        if (!Storage::disk('local')->exists('data.txt')) {
            return response()->json(['message' => 'El archivo no existe.'], 404);
        }

        // Leer el contenido del archivo
        $content = Storage::disk('local')->get('data.txt');

        return response()->json(['content' => $content]);
    }

    public function agregarCodigoTargeta() {}


    private function datosLector()
    {
        // Verifica si el archivo existe
        if (!Storage::disk('local')->exists('estadoLector.txt')) {
            // Si no existe, lo crea y escribe contenido inicial
            Storage::disk('local')->put('estadoLector.txt', '');
        }

        $estadoLector = Storage::disk('local')->get('estadoLector.txt');

        if ($estadoLector != "lectura") {
            return response()->json(
                [
                    'titulo' => "error",
                    'mensaje'  => "El estado del lector no esta en lectura",
                ],
                200
            );
        }
        // Verifica si el archivo existe
        if (!Storage::disk('local')->exists('data.txt')) {
            // Si no existe, lo crea y escribe contenido inicial
            Storage::disk('local')->put('data.txt', '');
        }


        // Obtiene el contenido del archivo
        $datosTargeta = Storage::disk('local')->get('data.txt');

        return $datosTargeta;
    }
}
