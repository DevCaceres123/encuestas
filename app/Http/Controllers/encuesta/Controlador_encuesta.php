<?php

namespace App\Http\Controllers\encuesta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
use Illuminate\Support\Facades\DB;
use App\Http\Requests\encuesta\EncuestaRequest;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Exception;

class Controlador_encuesta extends Controller
{
    /**
     * Display a listing of the resource.
     */


    // Nos redirecciona a la vista de inicio de encuesta

    public function index()
    {
        if (!auth()->user()->can('encuestas.inicio')) {
            return redirect()->route('inicio');
        }

        return view('administrador.encuesta.encuesta');
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

    // Se crea una  nueva encuesta
    public function store(EncuestaRequest $request)
    {
        DB::beginTransaction();
        try {
            $encuesta = new Encuesta();

            $encuesta->titulo = $request->titulo;
            $encuesta->descripcion = $request->descripcion;
            $encuesta->estado = 'activo';
            $encuesta->user_id = auth()->user()->id;
            $encuesta->save();

            DB::commit();

            $this->mensaje('exito', 'Encuesta creada correctamente');

            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {
            DB::rollBack();

            $this->mensaje('error', 'error' . $e->getMessage());

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
    public function edit(string $id_afiliado)
    {

        try {

            // Encontrar el usuario por ID
            $encuesta = Encuesta::findOrFail($id_afiliado);

            if (!$encuesta) {
                throw new Exception('Encuesta no encontrado');
            }

            $this->mensaje("exito", $encuesta);

            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {
            // Revertir los cambios si hay algún error

            $this->mensaje("error", "error" . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */

    //  eliminamos una pretgunta de la encuesta
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $pregunta = Pregunta::find($id);
            if (!$pregunta) {
                throw new Exception('pregunta no encontrado');
            }
            $pregunta->delete();
            DB::commit();

            $this->mensaje('exito', 'Pregunta eliminado correctamente');

            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {
            DB::rollBack();

            $this->mensaje('error', 'Error' . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
    }


    // Listar encuestas con paginación y búsqueda
    public function listarEncuesta(Request $request)
    {
        $query = Encuesta::select('id', 'titulo', 'descripcion', 'estado', 'created_at');

        // Filtro de búsqueda: Filtra por los campos correctos en la tabla encuesta
        if (!empty($request->search['value'])) {
            $query->where('titulo', 'like', '%' . $request->search['value'] . '%')->orWhere('descripcion', 'like', '%' . $request->search['value'] . '%');
        }

        // Total de registros antes del filtrado
        $recordsTotal = $query->count();

        // Paginación y orden
        $encuestas = $query->skip($request->start)->take($request->length)->get();

        // Respuesta
        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal, // Ajustar si hay filtros
            'data' => $encuestas,
            'permisos' => [
                'editar' => auth()->user()->can('encuestas.editar'),
                'eliminar' => auth()->user()->can('encuestas.eliminar'),
                'estado' => auth()->user()->can('encuestas.estado'),
                'ver_preguntas' => auth()->user()->can('encuestas.ver_preguntas'),
                'ver_informe' => auth()->user()->can('encuestas.ver_informe'),
            ],
        ]);
    }


    // SE LISTARAN TODAS LAS PREGUNTAS DE UNA ENCUESTA
    public function verEncuesta(Request $request, $id_encuesta)
    {
        if (!auth()->user()->can('encuestas.encuestas.inicio')) {
            return redirect()->route('inicio');
        }

        $encuesta = Encuesta::find($id_encuesta);

        if (!$encuesta) {
            return redirect()->route('inicio');
        }
        $preguntas = $this->obtenerPreguntasEncuesta($encuesta->id);

        return view('administrador.encuesta.editarEncuesta', compact('preguntas', 'encuesta'));
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

    // Guarda una nueva pregunta en una encuesta
    // Esta función recibe los datos de la pregunta y los guarda en la base de datos
    public function guardarPregunta(EncuestaRequest $request, $id_encuesta)
    {
        DB::beginTransaction();

        try {
            $encuesta = Encuesta::find($id_encuesta);

            if (!$encuesta) {
                throw new Exception('encuesta no válida');
            }
            $this->guardarTipoDePregunta($request->tipoPregunta, $request->descripcionPregunta, $id_encuesta, $request->opciones, $request->obligatorio, $request->varias_respuestas);

            DB::commit();
            $this->mensaje('exito', 'Pregunta guardada correctamente');

            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {
            DB::rollBack();
            $this->mensaje('error', 'Error ' . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
    }

    // Guarda el tipo de pregunta según su tipo
    public function guardarTipoDePregunta($tipo, $descripcion, $id_encuesta, $opciones, $obligatorio, $varias_respuestas)
    {
        // Lista de tipos permitidos
        $tiposPermitidos = ['texto', 'numerico', 'opcional', 'tabla'];

        // Validar que el tipo esté permitido
        if (!in_array($tipo, $tiposPermitidos)) {
            throw new Exception('pregunta no válida');
        }

        switch ($tipo) {
            case 'opcional':
                $this->guardarPreguntaConOpciones($tipo, $descripcion, $id_encuesta, $opciones, $obligatorio, $varias_respuestas);
                break;

            default:
                $this->guardarPreguntaBasica($tipo, $descripcion, $id_encuesta, $obligatorio);
                break;
        }
    }


    // Guarda una pregunta básica (texto, numérico, etc.) en la base de datos
    private function guardarPreguntaBasica($tipo, $descripcion, $id_encuesta, $obligatorio)
    {
        $maxOrden = Pregunta::max('orden') ?? 0;
        $pregunta = new Pregunta();
        $pregunta->titulo = $descripcion;
        $pregunta->tipo = $tipo;
        $pregunta->encuesta_id = $id_encuesta;
        $pregunta->obligatorio = $obligatorio;
        $pregunta->orden = $maxOrden + 1;
        $pregunta->save();
        if (!$pregunta) {
            throw new Exception('No se pudo registrar la pregunta.');
        }
    }



    // Guarda una pregunta de tipo 'opcional' con sus opciones
    private function guardarPreguntaConOpciones($tipo, $descripcion, $id_encuesta, $opciones, $obligatorio, $varias_respuestas)
    {
        $maxOrden = Pregunta::max('orden') ?? 0;
        $pregunta = new Pregunta();
        $pregunta->titulo = $descripcion;
        $pregunta->tipo = $tipo;
        $pregunta->encuesta_id = $id_encuesta;
        $pregunta->obligatorio = $obligatorio;
        $pregunta->varias_respuestas = $varias_respuestas; // Si no se envía, por defecto es 'no'
        $pregunta->orden = $maxOrden + 1;
        $pregunta->save();

        if (!$pregunta) {
            throw new Exception('No se pudo registrar la pregunta.');
        }
        //Guardar las opciones de respuesta
        $now = Carbon::now();
        $datos = [];
        foreach ($opciones as $texto) {
            $datos[] = [
                'pregunta_id' => $pregunta->id,
                'opcion' => $texto,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        Pregunta_opciones::insert($datos);
    }


    // Guarda una pregunta tipo tabla
    public function guardarPreguntaTabla(Request $request, $id_encuesta)
    {
        DB::beginTransaction();

        try {
            // 1. Crear la pregunta tipo tabla
            $maxOrden = Pregunta::max('orden') ?? 0;
            $pregunta = new Pregunta();
            $pregunta->titulo = $request->titulo;
            $pregunta->tipo = 'tabla';
            $pregunta->encuesta_id = $id_encuesta;
            $pregunta->obligatorio = 'si';
            $pregunta->orden = $maxOrden + 1;
            $pregunta->save();

            if (!$pregunta) {
                throw new Exception('No se pudo registrar la pregunta.');
            }

            $columnasParaInsertar = [];
            $filasParaInsertar = [];
            $opcionesParaInsertar = [];

            // 2. Preparar columnas para insert masivo
            foreach ($request->columnas as $columnaData) {
                $columnasParaInsertar[] = [
                    'pregunta' => $columnaData['titulo'],
                    'tipo' => $columnaData['tipo'],
                    'orden' => $columnaData['orden'],
                    'pregunta_id' => $pregunta->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // 3. Preparar filas si es tipo "pregunta"
                if ($columnaData['tipo'] === 'pregunta' && isset($columnaData['preguntas'])) {
                    foreach ($columnaData['preguntas'] as $filaData) {
                        if (empty(trim($filaData['texto']))) {
                            throw new Exception("Una subpregunta está vacía en la columna '{$columnaData['titulo']}'.");
                        }

                        $filasParaInsertar[] = [
                            'pregunta' => $filaData['texto'],
                            'orden' => $filaData['orden_fila'],
                            'pregunta_id' => $pregunta->id,
                            'orden_columna' => $columnaData['orden'], // temporal para mapear luego
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }

                // 4. Preparar opciones si es tipo "opcion" y simno esta vacio
                if ($columnaData['tipo'] === 'opcion' && isset($columnaData['opciones'])) {
                    foreach ($columnaData['opciones'] as $opcion) {
                        if (empty(trim($opcion))) {
                            throw new Exception("Una opción está vacía en la columna '{$columnaData['titulo']}'.");
                        }

                        $opcionesParaInsertar[] = [
                            'orden_columna' => $columnaData['orden'], // temporal
                            'opcion' => $opcion,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }

            // 5. Insertar columnas masivamente
            Pregunta_columna::insert($columnasParaInsertar);

            // 6. Obtener columnas recién insertadas
            $columnasGuardadas = Pregunta_columna::where('pregunta_id', $pregunta->id)->get();

            // 7. Mapear filas a columnas
            foreach ($filasParaInsertar as &$fila) {
                $columna = $columnasGuardadas->firstWhere('orden', $fila['orden_columna']);
                if (!$columna) {
                    throw new Exception("No se encontró la columna para la fila con orden columna {$fila['orden_columna']}");
                }
                $fila['columna_id'] = $columna->id;
                unset($fila['orden_columna']);
            }

            // 8. Mapear opciones a columna
            foreach ($opcionesParaInsertar as &$opcion) {
                $columna = $columnasGuardadas->firstWhere('orden', $opcion['orden_columna']);
                if (!$columna) {
                    throw new Exception("No se encontró la columna para la opción con orden columna {$opcion['orden_columna']}");
                }
                $opcion['columna_id'] = $columna->id;
                unset($opcion['orden_columna']);
            }

            // 9. Insertar filas y opciones
            if (!empty($filasParaInsertar)) {
                Pregunta_filas::insert($filasParaInsertar);
            }

            if (!empty($opcionesParaInsertar)) {
                Pregunta_opciones_columna::insert($opcionesParaInsertar);
            }

            DB::commit();

            $this->mensaje('exito', 'Pregunta guardada correctamente');
            return response()->json($this->mensaje, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->mensaje('error', 'Error ' . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
    }


    // Obtiene una pregunta específica de una encuesta
    public function obtenerPreguntaEncuesta($id_encuesta)
    {
        DB::beginTransaction();
        try {
            $pregunta = Pregunta::find($id_encuesta);
            if (!$pregunta) {
                throw new Exception('Pregunta no encontrada');
            }

            if ($pregunta->tipo === 'tabla') {
                $pregunta = $this->obtenerPreguntaTabla($pregunta->id);
            }

            DB::commit();

            $this->mensaje('exito', $pregunta);

            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {
            DB::rollBack();

            $this->mensaje('error', 'Error ' . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
    }

    // Obtiene la estructura de una pregunta tipo tabla
    public function obtenerPreguntaTabla($id)
    {
        $pregunta = Pregunta::with(['columnas.preguntas']) // columnas -> preguntas = filas
            ->where('id', $id)
            ->firstOrFail();

        $tieneRespuestas = Respuestas_tabla::where('pregunta_id', $id)->exists();

        $estructura = $this->formatearPreguntaTabla($pregunta, $tieneRespuestas);

        return $estructura;
    }

    // Guarda los cambios realizados en una pregunta
    public function guardarPreguntaEditada(Request $request, $id_pregunta)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'id' => 'required|exists:preguntas,id',
                'titulo' => 'required|string|max:50',
            ]);

            $pregunta = Pregunta::find($request->id);
            if (!$pregunta) {
                throw new Exception('Pregunta no encontrada');
            }

            if ($pregunta->tipo == 'opcional') {
                $pregunta->titulo = $request->titulo;
                $pregunta->obligatorio = $request->obligatorio;
                $pregunta->varias_respuestas = $request->masDeunaOpcion;
                $pregunta->save();

                $this->actualizarOpciones($request, $pregunta);
            }

            if ($pregunta->tipo == 'numerico' || $pregunta->tipo == 'texto') {
                $pregunta->titulo = $request->titulo;
                $pregunta->obligatorio = $request->obligatorio;
                $pregunta->save();
            }

            if ($pregunta->tipo === 'tabla') {
                $pregunta->titulo = $request->titulo;
                $pregunta->obligatorio = $request->obligatorio;
                $pregunta->save();
                $this->actualizarPreguntaTabla($request, $pregunta);
            }

            DB::commit();

            $this->mensaje('exito', 'Pregunta editada correctamente');

            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {
            DB::rollBack();

            $this->mensaje('error', 'Error ' . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
    }

    // Actualiza las opciones de una pregunta tipo 'opcional'
    public function actualizarOpciones($request, Pregunta $pregunta)
    {

        $opcionesEnviadas = $request->opciones; // contiene id y texto

        // Traer todas las opciones actuales
        $opcionesActuales = Pregunta_opciones::where('pregunta_id', $pregunta->id)->get()->keyBy('id');

        // IDs recibidos (de opciones que siguen existiendo)
        $idsRecibidos = [];

        foreach ($opcionesEnviadas as $opcion) {
            $texto = trim($opcion['texto']);
            $id = $opcion['id'] ?? null;

            if ($id) {
                $idsRecibidos[] = $id;

                if (isset($opcionesActuales[$id]) && $opcionesActuales[$id]->opcion !== $texto) {
                    // Actualizar texto si cambió
                    $opcionesActuales[$id]->opcion = $texto;
                    $opcionesActuales[$id]->save();
                }
            } else {
                // Insertar nueva opción
                Pregunta_opciones::create([
                    'pregunta_id' => $pregunta->id,
                    'opcion' => $texto,
                ]);
            }
        }

        // Opciones que no están en el request => podrían haberse eliminado
        $idsExistentes = $opcionesActuales->keys();
        $idsParaEliminar = $idsExistentes->diff($idsRecibidos);

        foreach ($idsParaEliminar as $id) {
            $opcion = $opcionesActuales[$id];



            $opcion->delete();

        }


    }

    // Actualiza las columnas y subdatos de una pregunta tipo tabla
    private function actualizarPreguntaTabla(Request $request, Pregunta $pregunta)
    {

        $columnasEnviadas = $request->columnas;

        // Obtener columnas actuales
        $columnasActuales = Pregunta_columna::where('pregunta_id', $pregunta->id)->get()->keyBy('id');

        $idsRecibidos = [];

        foreach ($columnasEnviadas as $col) {
            $idCol = $col['id'] ?? null;
            $titulo = trim($col['titulo']);
            $tipoCol = $col['tipo'];
            $orden = $col['orden'];


            if ($idCol && isset($columnasActuales[$idCol])) {

                // Actualizar columna existente
                $columna = $columnasActuales[$idCol];
                $columna->update([
                    'pregunta' => $titulo,
                    'tipo' => $tipoCol,
                    'orden' => $orden,
                ]);
            } else {
                // Crear nueva columna

                $columna = Pregunta_columna::create([
                    'pregunta_id' => $pregunta->id,
                    'pregunta' => $titulo,
                    'tipo' => $tipoCol,
                    'orden' => $orden,
                ]);
            }

            $idsRecibidos[] = $columna->id;

            // === Subdatos ===


            // 1. Opciones de columna tipo 'opcion'
            if ($tipoCol === 'opcion' && isset($col['opciones'])) {

                $opcionesActuales = Pregunta_opciones_columna::where('columna_id', $columna->id)->get()->keyBy('id');
                $idsOpciones = [];

                foreach ($col['opciones'] as $op) {
                    $idOp = $op['id'] ?? null;
                    $texto = trim($op['texto']);

                    if ($idOp !== null && isset($opcionesActuales[$idOp])) {
                        $opExistente = $opcionesActuales[$idOp];

                        if ($opExistente->opcion !== $texto) {
                            $opExistente->opcion = $texto;
                            $opExistente->save();
                        }
                    } else {

                        $nueva = Pregunta_opciones_columna::create([
                            'columna_id' => $columna->id,
                            'opcion' => $texto,
                        ]);
                        $idOp = $nueva->id;
                    }

                    $idsOpciones[] = $idOp;
                }

                // Eliminar opciones no enviadas
                $opcionesAEliminar = $opcionesActuales->keys()->diff($idsOpciones);
                Pregunta_opciones_columna::whereIn('id', $opcionesAEliminar)->delete();
            }

            // 2. Preguntas de columna tipo 'pregunta'
            if ($tipoCol === 'pregunta' && isset($col['preguntas'])) {

                $filasActuales = Pregunta_filas::where('columna_id', $columna->id)->get()->keyBy('id');
                $idsFilas = [];

                foreach ($col['preguntas'] as $fila) {
                    $idFila = $fila['id'] ?? null;
                    $texto = trim($fila['texto']);
                    $orden_fila = $fila['orden_fila'] ?? 0;

                    if ($idFila !== null && isset($filasActuales[$idFila])) {

                        $fExistente = $filasActuales[$idFila];
                        if ($fExistente->texto !== $texto || $fExistente->orden_fila != $orden_fila) {
                            $fExistente->update([
                                'pregunta' => $texto,
                                'orden' => $orden_fila,
                            ]);
                        }
                    } else {

                        $nueva = Pregunta_filas::create([
                            'columna_id' => $columna->id,
                            'pregunta' => $texto,
                            'orden' => $orden_fila,
                            'pregunta_id' => $pregunta->id,
                        ]);
                        $idFila = $nueva->id;
                    }

                    $idsFilas[] = $idFila;
                }

                // Eliminar filas no enviadas
                $filasAEliminar = $filasActuales->keys()->diff($idsFilas);
                Pregunta_filas::whereIn('id', $filasAEliminar)->delete();
            }
        }

        // Eliminar columnas no enviadas
        $columnasAEliminar = $columnasActuales->keys()->diff($idsRecibidos);
        Pregunta_columna::whereIn('id', $columnasAEliminar)->delete();


    }


    // En este  funcion realizamos el cambio de orden de las preguntas dentro de una encuesta

    public function cambiarOrden(Request $request, $encuesta_id)
    {

        DB::beginTransaction();
        try {

            $request->validate([
        'preguntaMoverId' => 'required|exists:preguntas,id',
        'destinoId' => 'required|exists:preguntas,id',
        'accion' => 'required|in:antes,despues',
        ]);

            $preguntaMover = Pregunta::find($request->preguntaMoverId);
            $preguntaDestino = Pregunta::find($request->destinoId);

            if ($preguntaMover->id === $preguntaDestino->id) {
                return response()->json(['tipo' => 'error', 'mensaje' => 'No puedes mover la pregunta sobre sí misma.']);
            }

            // Ajustar orden
            $nuevoOrden = $request->accion === 'antes'
                ? $preguntaDestino->orden
                : $preguntaDestino->orden + 1;

            // Mover la pregunta
            $preguntaMover->orden = $nuevoOrden;
            $preguntaMover->save();

            // Reordenar el resto
            $preguntas = Pregunta::where('encuesta_id', $encuesta_id)
                ->where('id', '!=', $preguntaMover->id)
                ->orderBy('orden')
                ->get();

            $orden = 0;
            foreach ($preguntas as $pregunta) {
                if ($orden === $nuevoOrden) {
                    $orden++;
                } // saltamos espacio para la movida
                $pregunta->orden = $orden++;
                $pregunta->save();
            }

            // Finalmente, la pregunta movida
            $preguntaMover->orden = $nuevoOrden;
            $preguntaMover->save();

            DB::commit();

            $this->mensaje('exito', 'Orden actualizado correctamente');

            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {
            DB::rollBack();

            $this->mensaje('error', 'Error: ' . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }




        return response()->json(['mensaje' => 'Orden actualizado']);
    }





    // SECCION DE INFORME

    public function verInforme($id_encuesta)
    {

        // if (!auth()->user()->can('encuestas.informe.ver')) {
        //     return redirect()->route('inicio');
        // }

        $encuesta = Encuesta::find($id_encuesta);

        if (!$encuesta) {
            return redirect()->route('inicio');
        }

        // Buscar o crear informe
        $informe = Informe::firstOrCreate(
            ['encuesta_id' => $id_encuesta],
            ['titulo' => 'Informe generado automáticamente']
        );



        $informe_id = $informe->id;

        //Obtener preguntas relevantes con relaciones necesarias
        $preguntas = Pregunta::where('encuesta_id', $id_encuesta)
            ->whereIn('tipo', ['numerico', 'opcional', 'tabla'])
            ->with(['columnas' => function ($q) {
                $q->whereIn('tipo', ['numero', 'opcion', 'porcentaje', 'pregunta'])
                  ->with('preguntas');
            }])
            ->get();

        $campos = [];

        foreach ($preguntas as $pregunta) {
            if ($pregunta->tipo === 'tabla') {
                foreach ($pregunta->columnas as $columna) {
                    if ($columna->tipo === 'pregunta') {
                        foreach ($columna->preguntas as $fila) {
                            $campos[] = [
                                'informe_id' => $informe_id,
                                'pregunta_id' => $pregunta->id,
                                'columna_id' => $columna->id,
                                'fila_id' => $fila->id,
                                'tipo_analisis' => null,
                                'titulo' => "{$pregunta->titulo} - {$columna->pregunta} - {$fila->pregunta}",
                                'estado' => 'activo',
                            ];
                        }
                    } else {
                        $campos[] = [
                            'informe_id' => $informe_id,
                            'pregunta_id' => $pregunta->id,
                            'columna_id' => $columna->id,
                            'fila_id' => null,
                            'tipo_analisis' => null,
                            'titulo' => "{$pregunta->titulo} - {$columna->pregunta}",
                            'estado' => 'activo',
                        ];
                    }
                }
            } else {
                $campos[] = [
                    'informe_id' => $informe_id,
                    'pregunta_id' => $pregunta->id,
                    'columna_id' => null,
                    'fila_id' => null,
                    'tipo_analisis' => null,
                    'titulo' => $pregunta->titulo,
                    'estado' => 'activo',
                ];
            }
        }

        // 3️⃣ Limpiar campos antiguos del informe antes de insertar
        InformeCampos::where('informe_id', $informe_id)->delete();

        // 4️⃣ Insertar nuevos campos
        InformeCampos::insert($campos);

        $preguntas = InformeCampos::with(['pregunta', 'columna', 'fila'])
            ->where('informe_id', $informe_id)
            ->orderBy('id')
            ->get();


        return view('administrador.encuesta.informe', [
            'encuesta' => $encuesta,
            'informe' => $informe,
            'campos' => $campos,
            'preguntas' => $preguntas,
        ]);
    }

    public function eliminarEncuesta($id_encuesta)
    {

        DB::beginTransaction();
        try {
            $encuesta = Encuesta::find($id_encuesta);
            if (!$encuesta) {
                throw new Exception('encuesta no encontrado');
            }
            $encuesta->delete();
            DB::commit();

            $this->mensaje('exito', 'Encuesta eliminado correctamente');

            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {
            DB::rollBack();

            $this->mensaje('error', 'Error' . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
    }

    public function actualizarEncuesta(EncuestaRequest $request, $id_encuesta)
    {
        DB::beginTransaction();
        try {
            $encuesta = Encuesta::find($id_encuesta);
            if (!$encuesta) {
                throw new Exception('Encuesta no encontrada');
            }

            $encuesta->titulo = $request->tituloEdit;
            $encuesta->descripcion = $request->descripcionEdit;
            $encuesta->save();

            DB::commit();

            $this->mensaje('exito', 'Encuesta actualizada correctamente');

            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {
            DB::rollBack();

            $this->mensaje('error', 'Error: ' . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
    }


    public function actualizarEstado(Request $request)
    {
        DB::beginTransaction();
        try {

            // Encontrar el usuario por ID
            $encuesta = Encuesta::findOrFail($request->id_afiliado);
            if (!$encuesta) {
                throw new Exception('Afiliado no encontrado');
            }
            if ($request->estado == "activo") {
                $encuesta->estado = "inactivo";
            }
            if ($request->estado == "inactivo") {
                $encuesta->estado = "activo";
            }


            $encuesta->save();
            DB::commit();

            $this->mensaje("exito", "Estado cambiado Correctamente");

            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {
            // Revertir los cambios si hay algún error
            DB::rollBack();

            $this->mensaje("error", "error" . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
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
