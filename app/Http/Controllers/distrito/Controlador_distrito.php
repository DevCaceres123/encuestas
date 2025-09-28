<?php

namespace App\Http\Controllers\distrito;

use App\Http\Controllers\Controller;
use App\Http\Requests\distrito\DistritoRequest;
use App\Models\Comunidad;
use App\Models\Distrito;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;




class Controlador_distrito extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public $mensaje = [];
    public function index()
    {
        if (!auth()->user()->can('distrito_comunidad.inicio')) {
            return redirect()->route('inicio');
        }


        $distritos = Distrito::select('titulo', 'id')->get();
        return view('administrador.distrito.distrito', compact('distritos'));
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
    public function store(DistritoRequest $request)
    {
        DB::beginTransaction();
        try {
            $distrito = new Distrito();
            $distrito->titulo = $request->titulo_distrito;
            $distrito->descripcion = $request->descripcion_distrito;
            $distrito->save();
            DB::commit();

            $this->mensaje("exito", "Distrito creado correctamente");

            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {
            DB::rollBack();

            $this->mensaje("error", "error" . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
    }


    // registra una nueva comunidad

    public function nuevaComunidad(DistritoRequest $request)
    {

        DB::beginTransaction();
        try {
            $comunidades = new Comunidad();
            $comunidades->titulo = $request->titulo_comunidad;
            $comunidades->descripcion = $request->descripcion_comunidad;
            $comunidades->distrito_id = $request->distrito_nombre;
            $comunidades->save();
            DB::commit();

            $this->mensaje("exito", "Comunidad creado correctamente");

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
        DB::beginTransaction();
        try {
            $distrito = Distrito::select('id', 'titulo', 'descripcion')->first($id);
            if (!$distrito) {
                throw new Exception('Distrito no encontrado');
            }



            $this->mensaje("exito", $distrito);

            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {


            $this->mensaje("error", "error" . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
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

        DB::beginTransaction();
        try {
            $distrito = Distrito::find($id);
            if (!$distrito) {
                throw new Exception('Distrito no encontrado');
            }

            $distrito->titulo = $request->titulo;
            $distrito->descripcion = $request->descripcion;

            $distrito->save();

            DB::commit();
            $this->mensaje("exito", "editado correctamente");

            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {

            DB::rollBack();
            $this->mensaje("error", "error" . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
    }

    public function listarDistrito()
    {
        
        $distritos = Distrito::select('id', 'titulo', 'descripcion')->get();
        $permissions = [
            'editar' => auth()->user()->can('distrito_comunidad.distrito.editar'),
            'eliminar' => auth()->user()->can('distrito_comunidad.distrito.eliminar'),
        ];
        return [
            'permisos' => $permissions,
            'distrito' => $distritos,
        ];
    }


    public function listarComunidad()
    {
        $comunidades = Comunidad::select('id', 'titulo', 'descripcion', 'distrito_id')->with(['distrito:id,titulo'])->get();

        $permissions = [
            'editar' => auth()->user()->can('distrito_comunidad.comunidad.editar'),
            'eliminar' => auth()->user()->can('distrito_comunidad.comunidad.eliminar'),
        ];
        return [
            'permisos' => $permissions,
            'comunidades' => $comunidades,
        ];
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $distrito = Distrito::find($id);
            if (!$distrito) {
                throw new Exception('Distrito no encontrado');
            }
            $distrito->delete();
            DB::commit();

            $this->mensaje("exito", "Distrito eliminado correctamente");

            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {
            DB::rollBack();

            $this->mensaje("error", "error" . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
    }

    public function eliminar_comunidad(string $id)
    {

        DB::beginTransaction();
        try {
            $distrito = Comunidad::find($id);
            if (!$distrito) {
                throw new Exception('Comunidad no encontrado');
            }
            $distrito->delete();
            DB::commit();

            $this->mensaje("exito", "Comunidad eliminado correctamente");

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
