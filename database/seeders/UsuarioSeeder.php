<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $rol1       = new Role();
        $rol1->name = 'administrador';
        $rol1->save();

        $rol2       = new Role();
        $rol2->name = 'general';
        $rol2->save();

        $usuario = new User();
        $usuario->usuario = 'admin';
        $usuario->password = Hash::make('rodry');
        $usuario->ci = '10028685';
        $usuario->nombres = 'wilmer';
        $usuario->paterno = 'villarroel';
        $usuario->materno = 'surco';
        $usuario->estado = 'activo';
        $usuario->email = 'rodrigo@gmail.com';
        $usuario->save();

        $usuario->syncRoles(['administrador']);


        Permission::create(['name' => 'inicio.index'])->syncRoles([$rol1 ,$rol2]);

        Permission::create(['name' => 'admin.index'])->syncRoles([$rol1, $rol2]);

        // USAURIO
        Permission::create(['name' => 'admin.usuario.inicio'])->syncRoles([$rol1]);
        Permission::create(['name' => 'admin.usuario.crear'])->assignRole($rol1);
        Permission::create(['name' => 'admin.usuario.desactivar'])->assignRole($rol1);
        Permission::create(['name' => 'admin.usuario.reset'])->assignRole($rol1);
        Permission::create(['name' => 'admin.usuario.edit'])->assignRole($rol1);


        //ROL
        Permission::create(['name' => 'admin.rol.inicio'])->syncRoles([$rol1]);
        Permission::create(['name' => 'admin.rol.crear'])->syncRoles([$rol1]);
        Permission::create(['name' => 'admin.rol.editar'])->syncRoles([$rol1]);
        Permission::create(['name' => 'admin.rol.eliminar'])->syncRoles([$rol1]);
        Permission::create(['name' => 'admin.rol.visualizar'])->syncRoles([$rol1]);

        //PERMISOS
        Permission::create(['name' => 'admin.permiso.inicio'])->syncRoles([$rol1]);
        Permission::create(['name' => 'admin.permiso.crear'])->syncRoles([$rol1]);
        Permission::create(['name' => 'admin.permiso.editar'])->syncRoles([$rol1]);
        Permission::create(['name' => 'admin.permiso.eliminar'])->syncRoles([$rol1]);

        // AFILIADOS
        Permission::create(['name' => 'afiliado.index'])->syncRoles([$rol1]);

        // ENCUESTAS
        Permission::create(['name' => 'encuestas.index'])->syncRoles([$rol1]);


         // FORMULARIO
         Permission::create(['name' => 'formulario.index'])->syncRoles([$rol1]);

        //  DISTRITO Y COMUNIDAD
         Permission::create(['name' => 'distrito_comunidad.index'])->syncRoles([$rol1]);

    }
}
