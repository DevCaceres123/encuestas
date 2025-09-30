<?php

namespace App\Http\Controllers\afiliado;

use App\Http\Controllers\Controller;
use App\Http\Requests\Afiliado\AfiliadoRequest;
use App\Models\Afiliado;
use App\Models\Comunidad;
use App\Models\Expedido;
use App\Models\Miembros_familia;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Exception;
use PharIo\Manifest\Author;
use PhpParser\Node\Stmt\Return_;

class Controlador_afiliado extends Controller
{
    public $mensaje = [];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->can('afiliado')) {
            return redirect()->route('inicio');
        }

        $expedidos = Expedido::select('id', 'departamento')->get();
        $comunidadades = Comunidad::with(['distrito' => function ($query) {
            // Asegúrate de seleccionar 'afiliado_id' para que la relación funcione
            $query->select('id','titulo');
        }])->select('id', 'titulo','distrito_id')->get();
        return view('administrador.afiliado.afiliado', compact(['expedidos', 'comunidadades']));
    }


    public function listarAfiliado(Request $request)
    {

        $query = Afiliado::with([
        'numero_familia' => function ($query) {
            // Asegúrate de seleccionar 'afiliado_id' para que la relación funcione
            $query->select('total_integrantes', 'afiliado_id');
        },

        'comunidad' => function ($query) {
            // Asegúrate de seleccionar 'afiliado_id' para que la relación funcione
            $query->select('id', 'titulo');
        },
        
        ])->select('id', 'nombres', 'paterno', 'materno', 'ci','estado','comunidad_id');

        // Filtro de búsqueda: Filtra por los campos correctos en la tabla Afiliado
        if (!empty($request->search['value'])) {
            $query->where('nombres', 'like', '%' . $request->search['value'] . '%')
                ->orWhere('paterno', 'like', '%' . $request->search['value'] . '%')
                ->orWhere('materno', 'like', '%' . $request->search['value'] . '%')
                ->orWhere('ci', 'like', '%' . $request->search['value'] . '%');
        }

        // Total de registros antes del filtrado
        $recordsTotal = $query->count();

        // Paginación y orden
        $afiliados = $query
            ->skip($request->start)
            ->take($request->length)
            ->get();

        // Respuesta
        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal, // Ajustar si hay filtros
            'data' => $afiliados,
            'permisos' => [
                'editar' => auth()->user()->can('afiliado.editar'),
                'eliminar' => auth()->user()->can('afiliado.eliminar'),
                'estado' => auth()->user()->can('afiliado.estado'),
            ]
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
    public function store(AfiliadoRequest $request)
    {
        DB::beginTransaction();
        try {
            $afiliado = new Afiliado();

            $afiliado->nombres = $request->nombres;
            $afiliado->paterno = $request->paterno;
            $afiliado->materno = $request->materno;
            $afiliado->ci = $request->complemento == null ? $request->ci : $request->ci . "-" . $request->complemento;
            $afiliado->estado='activo';
            $afiliado->expedido_id  = $request->expedido_id;
            $afiliado->comunidad_id = $request->comunidad_id;
            $afiliado->user_id = auth()->user()->id;
            $afiliado->save();

            $miembrosFamilia = new Miembros_familia();
            $miembrosFamilia->hombres = $request->hombres;
            $miembrosFamilia->mujeres = $request->mujeres;
            $miembrosFamilia->total_integrantes = $request->hombres + $request->mujeres;
            $miembrosFamilia->afiliado_id = $afiliado->id;

            $miembrosFamilia->save();

            DB::commit();

            $this->mensaje("exito", "Afiliado registrado correctamente");

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
    public function edit(string $id_afiliado)
    {
        DB::beginTransaction();
        try {

            // Encontrar el usuario por ID
            $afiliado = Afiliado::findOrFail($id_afiliado);

            if (!$afiliado) {
                throw new Exception('Afiliado no encontrado');
            }
            $miembrosFamilia= Miembros_familia::select('mujeres','hombres')->where('afiliado_id',$afiliado->id)->first();
            $afiliado['miembrosFamilia']=$miembrosFamilia;
            DB::commit();

            $this->mensaje("exito", $afiliado);

            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {
            // Revertir los cambios si hay algún error
            DB::rollBack();

            $this->mensaje("error", "error" . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
    }

    // cambiar estado afiliado
    public function update(AfiliadoRequest $request)
    {
        DB::beginTransaction();
        try {

            // Encontrar el usuario por ID
            $afiliado = Afiliado::findOrFail($request->id_afiliado);
            if (!$afiliado) {
                throw new Exception('Afiliado no encontrado');
            }
            if ($request->estado == "activo") {
                $afiliado->estado = "inactivo";
            }
            if ($request->estado == "inactivo") {
                $afiliado->estado = "activo";
            }


            $afiliado->save();
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



    public function actualizarAfiliado(AfiliadoRequest $request, string $afiliado_id)
    {
        DB::beginTransaction();
        try {
            // Buscar afiliado por su ID
            $afiliado = Afiliado::find($afiliado_id); // Usar $afiliado_id en lugar de $request->id_afiliado
            if (!$afiliado) {
                throw new Exception('Afiliado no encontrado');
            }
    
            // Actualizar afiliado
            $afiliado->nombres = $request->input('nombres-edit');
            $afiliado->paterno = $request->input('paterno-edit');
            $afiliado->materno = $request->input('materno-edit');
            $afiliado->ci = $request->input('complemento-edit') == null 
                ? $request->input('ci-edit') 
                : $request->input('ci-edit') . "-" . $request->input('complemento-edit');
    
            $afiliado->expedido_id  = $request->input('expedido_id-edit');
            $afiliado->comunidad_id = $request->input('comunidad_id-edit');
    
            $afiliado->save();
    
            // Buscar o crear miembros de familia
            $miembrosFamilia = Miembros_familia::where('afiliado_id', $afiliado_id)->first();
            if (!$miembrosFamilia) {
                throw new Exception('Miembro de la familia no encontrado');
            }
    
            // Actualizar miembros de familia
            $miembrosFamilia->hombres = $request->input('hombres-edit');
            $miembrosFamilia->mujeres = $request->input('mujeres-edit');
            $miembrosFamilia->total_integrantes = $request->input('hombres-edit') + $request->input('mujeres-edit');
    
            $miembrosFamilia->save(); // Guardar cambios
    
            DB::commit();
            $this->mensaje("exito", "Datos actualizados Correctamente");
            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {
            // Revertir los cambios si hay algún error
            DB::rollBack();
    
            $this->mensaje("error", "error: " . $e->getMessage());
    
            return response()->json($this->mensaje, 200);
        }
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {


        DB::beginTransaction();
        try {
            $afiliado = Afiliado::find($id);
            if (!$afiliado) {
                throw new Exception('Afiliado no encontrado');
            }
            $cantidadFamilia = Miembros_familia::find($afiliado->id);
            $afiliado->delete();
            $cantidadFamilia->delete();
            DB::commit();

            $this->mensaje("exito", "Afiliado eliminado correctamente");

            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {
            DB::rollBack();

            $this->mensaje("error", "error" . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
    }

    public function mensaje($titulo, $mensaje)
    {

        $this->mensaje = [
            'tipo' => $titulo,
            'mensaje' => $mensaje
        ];
    }
}
