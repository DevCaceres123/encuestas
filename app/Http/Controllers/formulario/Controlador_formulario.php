<?php

namespace App\Http\Controllers\formulario;

use App\Http\Controllers\Controller;
use App\Http\Requests\Formulario\FormularioRequest;
use Illuminate\Http\Request;
use App\Models\Formulario;
use App\Models\Afiliado;
use App\Models\Encuesta;
use App\Models\Pregunta;
use App\Models\Pregunta_columna;
use App\Models\Pregunta_filas;
use App\Models\Pregunta_opciones;
use App\Models\Pregunta_opciones_columna;
use App\Models\Respuestas_tabla;
use App\Models\Respuesta;
use App\Models\Informe;
use App\Models\InformeCampos;
use Exception;
use Illuminate\Support\Facades\DB;

class Controlador_formulario extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->can('formulario.inicio')) {
            return redirect()->route('inicio');
        }

        $encuestas=Encuesta::where('estado','activo')->get();
        return view('administrador.formulario.formulario',compact(['encuestas']));
    }


    public function listarFormularios(Request $request)
    {
        $query = Formulario::select('id', 'titulo_formulario', 'descripcion_formulario', 'estado', 'created_at')->orderby('created_at', 'desc');

        // Filtro de búsqueda: Filtra por los campos correctos en la tabla encuesta
        if (!empty($request->search['value'])) {
            $query->where('titulo_formulario', 'like', '%' . $request->search['value'] . '%')->orWhere('descripcion_formulario', 'like', '%' . $request->search['value'] . '%');
        }

        // Total de registros antes del filtrado
        $recordsTotal = $query->count();

        // Paginación y orden
        $formularios = $query->skip($request->start)->take($request->length)->get();

        // Respuesta
        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal, // Ajustar si hay filtros
            'data' => $formularios,
            'permisos' => [
                'editar' => auth()->user()->can('formulario.editar'),
                'eliminar' => auth()->user()->can('formulario.eliminar'),
                'estado' => auth()->user()->can('formulario.estado'),
                'responer_formulario' => auth()->user()->can('formulario.responer_formulario'),
                'ver_respuestas' => auth()->user()->can('formulario.ver_respuestas'),
            ],
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FormularioRequest $request)
    {
        
        DB::beginTransaction();
        try {
            $formulario = new Formulario();
            $formulario->titulo_formulario=$request->tituloFormulario;
            $formulario->descripcion_formulario=$request->tituloFormulario;
            $formulario->estado='proceso';
            $formulario->user_id=auth()->user()->id;
            $formulario->encuesta_id=$request->encuesta_id;
           
            $formulario->save();

            DB::commit();

            $this->mensaje("exito", "Formulario registrado correctamente");

            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {
            DB::rollBack();

            $this->mensaje("error", "error" . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }



    public function buscarAfiliado(string $datos_Afiliado)
    {


        $afiliados = Afiliado::where('ci', 'like', "%{$datos_Afiliado}%")
            ->orWhere('nombres', 'like', "%{$datos_Afiliado}%")
            ->limit(10)
            ->get(['id', 'ci', 'nombres', 'paterno', 'materno']);

        if ($afiliados->isEmpty()) {
            $this->mensaje('no_encontrado', 'No se encontraron afiliados con esos datos.');
        } else {
            $this->mensaje('exito', $afiliados);
        }


        return response()->json($this->mensaje, 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $formulario = Formulario::find($id);
            if (!$formulario) {
                throw new Exception('Formulario no encontrado');
            }
            
            $formulario->delete();            
            DB::commit();

            $this->mensaje("exito", "Formulario eliminado correctamente");

            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {
            DB::rollBack();

            $this->mensaje("error", "Error" . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
    }

    public function responderFormulario(string $id_formulario, string $id_afiliado)
    {

        try {

            // Validar que el formulario exista
            $formulario = Formulario::find($id_formulario);
            $afiliado = Afiliado::find($id_afiliado);
            $encuesta = Encuesta::find($formulario->encuesta_id);

            if (!$formulario) {
                throw new Exception('Formulario no encontrado');
            }
            if (!$afiliado) {
                throw new Exception('Afiliado no encontrado');
            }
            if (!$encuesta) {
                throw new Exception('Encuesta no encontrada para este formulario');
            }
            $preguntas = $this->obtenerPreguntasEncuesta($encuesta->id);

            return view('administrador.formulario.responder', compact('formulario', 'afiliado','preguntas'));


        } catch (Exception $e) {
            return redirect()->back()
            ->withInput() // Retorna valores previos si quieres
            ->with('error', 'Error: ' . $e->getMessage());
        }

    }





    // Obtiene las preguntas de una encuesta y su estructura
    public function obtenerPreguntasEncuesta($id_encuesta)
    {
        $preguntas = Pregunta::where('encuesta_id', $id_encuesta)
            ->with(['columnas', 'filas'])
            ->orderBy('orden')
            ->get();

        $estructura = [];

        foreach ($preguntas as $pregunta) {
            $tieneRespuestas;

            // verificar si la pregunta tiene respuestas
            if ($pregunta->tipo === 'tabla') {
                $tieneRespuestas = Respuestas_tabla::where('pregunta_id', $pregunta->id)->exists();
            } else {
                $tieneRespuestas = Respuesta::where('pregunta_id', $pregunta->id)->exists();
            }

            // se obtitiene la estructura de la pregunta
            if ($pregunta->tipo === 'tabla') {
                $estructura[] = $this->formatearPreguntaTabla($pregunta, $tieneRespuestas);
            } else {
                $item = [
                    'id' => $pregunta->id,
                    'tipo' => $pregunta->tipo,
                    'titulo' => $pregunta->titulo,
                    'obligatorio' => $pregunta->obligatorio,
                    'varias_respuestas' => $pregunta->varias_respuestas ?? 'no',
                ];

                if ($pregunta->tipo === 'opcional') {
                    $item['opciones'] = Pregunta_opciones::where('pregunta_id', $pregunta->id)
                        ->get(['id', 'opcion']) // Trae id y texto
                        ->toArray();
                }

                // Agregar el flag de edición

                $item['editable'] = $tieneRespuestas;
                $estructura[] = $item;
            }
        }

        return $estructura;
    }


    // Formatea la estructura de una pregunta tipo tabla
    // Esta función se encarga de estructurar los datos de una pregunta tipo tabla

    private function formatearPreguntaTabla($pregunta, $tieneRespuestas)
    {
        $estructura = [
            'id' => $pregunta->id,
            'tipo' => 'tabla',
            'titulo' => $pregunta->titulo,
            'columnas' => [],
            'obligatorio' => $pregunta->obligatorio,
            'editable' => $tieneRespuestas,
            'varias_respuestas' => $pregunta->varias_respuestas ?? 'no',
        ];

        foreach ($pregunta->columnas as $columna) {
            $columnaItem = [
                'id' => $columna->id,
                'tipo' => $columna->tipo,
                'titulo' => $columna->pregunta,
                'orden' => $columna->orden,
            ];

            if ($columna->tipo === 'pregunta') {
                $columnaItem['preguntas'] = $pregunta->filas
                    ->where('columna_id', $columna->id)
                    ->sortBy('orden')
                    ->values()
                    ->map(function ($fila) {
                        return [
                            'id' => $fila->id,
                            'texto' => $fila->pregunta,
                            'orden_fila' => $fila->orden,
                        ];
                    });
            }

            if ($columna->tipo === 'opcion') {
                $columnaItem['opciones'] = Pregunta_opciones_columna::where('columna_id', $columna->id)->get(['id', 'opcion']); // Trae id y texto

            }

            $estructura['columnas'][] = $columnaItem;
        }

        return $estructura;
    }


    // Mensaje para mostrar al usuario
    public function mensaje($titulo, $mensaje)
    {
        $this->mensaje = [
            'tipo' => $titulo,
            'mensaje' => $mensaje,
        ];
    }
}
