<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use App\Http\Requests\Perfil\UpdatePasswordRequest;
use App\Http\Requests\Usuario\Rol\UpdateRolRequest;
use App\Http\Requests\Usuario\Usuario\UsuarioRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Database\Seeders\UsuarioSeeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Support\Facades\Redis;
use PhpParser\Node\Stmt\TryCatch;

class Controlador_usuario extends Controller
{
    public $mensaje = [];

    public function index()
    {


        $usuarios = User::with('roles')->get();
        $roles = Role::select('id', 'name')->get();

        // Obtener el ID del usuario autenticado
        // $userId = auth()->user()->id; // O también puedes usar Auth::id();
        // $role = Auth::user()->getRoleNames(); // Devuelve una colección con los nombres de los roles
        return view('administrador.usuarios.usuarios', [
            'usuarios'  => $usuarios,
            'roles'  => $roles,
        ]);
    }


    public function show($id_usuario) {}


    public function store(UsuarioRequest $request)
    {

        DB::beginTransaction();

        try {
            // Crear un nuevo usuario
            $usuario = new User();
            $usuario->ci = $request->ci;
            $usuario->nombres = $request->nombres;
            $usuario->paterno = $request->paterno;
            $usuario->materno = $request->materno;
            $usuario->email = $request->email;
            $usuario->estado = "activo";
            $usuario->usuario = $request->usuario;
            $usuario->password = bcrypt($request->password);
            // $usuario->rol = $request->usuario_edad;

            // Guardar el nuevo usuario en la base de datos
            $usuario->save();


            $usuario->assignRole(intval($request->role));
            // Confirmar la transacción si todo va bien
            DB::commit();

            $this->mensaje("exito", "Usuario Registrado Correctamente");

            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {
            // Revertir los cambios si hay algún error
            DB::rollBack();

            $this->mensaje("error", "error" . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
    }

    public function update(UsuarioRequest $request, $id)
    {
        DB::beginTransaction();
        try {



            // Encontrar el usuario por ID
            $user = User::findOrFail($request->id_usaurio);

            if ($request->estado == "activo") {
                $user->estado = "inactivo";
            }
            if ($request->estado == "inactivo") {
                $user->estado = "activo";
            }


            $user->save();
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


    public function edit($id_usuario)
    {


        $user = User::select('id', 'ci', 'nombres', 'paterno', 'materno', 'usuario', 'password')->find($id_usuario);


        $this->mensaje("exito", $user);

        return response()->json($this->mensaje, 200);
    }



    public function resetar_usuario($id_usuario)
    {


        DB::beginTransaction();

        try {

            $user = User::find($id_usuario);

            $user->usuario = $user->ci;
            $user->password = Hash::make($user->ci . "_" . strtolower($user->nombres));

            $user->save();
            DB::commit();
            $this->mensaje("exito", "Usuario reseteado correctamente");

            return response()->json($this->mensaje, 200);
        } catch (Exception $e) {

            // Revertir los cambios si hay algún error
            DB::rollBack();

            $this->mensaje("error", "error" . $e->getMessage());

            return response()->json($this->mensaje, 200);
        }
    }


    public function editar_rol(UsuarioRequest $request)
    {

        $user = User::find($request->user_id);



        // Encuentra el rol por ID
        $nuevoRol = Role::find($request->rol_id);

        if (!$nuevoRol) {
            // Asigna el nuevo rol, eliminando cualquier rol anterior

            $this->mensaje("error", "Rol no encontrado");

            return response()->json($this->mensaje, 200);
        }

        $user->syncRoles([$nuevoRol->name]);


        $this->mensaje("exito", "Usuario reseteado correctamente");

        return response()->json($this->mensaje, 200);
    }



    public function listar()
    {
        $usuarios = User::with('roles')->get();

        $permissions = [
            'desactivar' => auth()->user()->can('admin.usuario.desactivar'),
            'reset' => auth()->user()->can('admin.usuario.reset'),

        ];
        return response()->json([
            'usuarios' => $usuarios,
            'permissions' => $permissions,
        ]);
    }
    /**
     * PARA LA PARTE DEL PERFIL
     */
    public function perfil()
    {
        $menu = 0;
        return view('administrador.perfil', [
            'menu' => $menu
        ]);
    }
    /**
     * FIN DE LA PARTE DE PERFIL
     */

    /**
     * PARA GUARDAR NUEVA CONTRASEÑA
     */
    public function password_guardar(UpdatePasswordRequest $request)
    {
        // Inicia la transacción
        DB::beginTransaction();
        try {
            $user = User::find(Auth::user()->id);

            // Verifica la contraseña actual
            if (!Hash::check($request->password_actual, $user->password)) {
                return response()->json(mensaje_mostrar('error', 'La contraseña actual no es correcta.'), 403);
            }

            // Verifica que las nuevas contraseñas coincidan
            if ($request->password_nuevo !== $request->password_confirmar) {
                return response()->json(mensaje_mostrar('error', 'Las contraseñas nuevas no coinciden.'), 422);
            }

            // Actualiza la contraseña
            $user->password = Hash::make($request->password_nuevo);
            $user->save();

            // Confirma la transacción
            DB::commit();

            return response()->json(mensaje_mostrar('success', 'Contraseña actualizada correctamente.'));
        } catch (\Exception $e) {
            // Si ocurre un error, deshacer todos los cambios realizados en la transacción
            DB::rollBack();
            return response()->json(mensaje_mostrar('error', 'Ocurrió un error al actualizar la contraseña.'), 500);
        }
    }


    public function mensaje($titulo, $mensaje)
    {

        $this->mensaje = [
            'tipo' => $titulo,
            'mensaje' => $mensaje
        ];
    }
    /**
     * FIN DE LA PARTE DE NUEVA CONTRASEÑA
     */
}
