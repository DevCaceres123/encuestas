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

        $usuario2 = new User();
        $usuario2->usuario = '1234';
        $usuario2->password = Hash::make('1234');
        $usuario2->ci = '123456789';
        $usuario2->nombres = 'michael';
        $usuario2->paterno = 'caceres';
        $usuario2->materno = 'quina';
        $usuario2->estado = 'activo';
        $usuario2->email = 'michael@gmail.com';
        $usuario2->save();

        $usuario2->syncRoles(['general']);


        Permission::create(['name' => 'inicio'])->syncRoles([$rol1 ,$rol2]);

        Permission::create(['name' => 'admin'])->syncRoles([$rol1, $rol2]);

        // USAURIO
        Permission::create(['name' => 'admin.usuario.inicio'])->syncRoles([$rol1]);
        Permission::create(['name' => 'admin.usuario.crear'])->assignRole($rol1);
        Permission::create(['name' => 'admin.usuario.desactivar'])->assignRole($rol1);
        Permission::create(['name' => 'admin.usuario.resetear'])->assignRole($rol1);
        Permission::create(['name' => 'admin.usuario.editar'])->assignRole($rol1);
        Permission::create(['name' => 'admin.usuario.cambiar_rol'])->assignRole($rol1);


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
        Permission::create(['name' => 'afiliado'])->syncRoles([$rol1]);
        Permission::create(['name' => 'afiliado.crear'])->syncRoles([$rol1]);
        Permission::create(['name' => 'afiliado.editar'])->syncRoles([$rol1]);
        Permission::create(['name' => 'afiliado.eliminar'])->syncRoles([$rol1]);
        Permission::create(['name' => 'afiliado.estado'])->syncRoles([$rol1]);

        // ENCUESTAS
        Permission::create(['name' => 'encuestas'])->syncRoles([$rol1]);
        Permission::create(['name' => 'encuestas.inicio'])->syncRoles([$rol1]);
        Permission::create(['name' => 'encuestas.crear'])->syncRoles([$rol1]);
        Permission::create(['name' => 'encuestas.editar'])->syncRoles([$rol1]);
        Permission::create(['name' => 'encuestas.eliminar'])->syncRoles([$rol1]);
        Permission::create(['name' => 'encuestas.estado'])->syncRoles([$rol1]);
        Permission::create(['name' => 'encuestas.ver_preguntas'])->syncRoles([$rol1]);
        Permission::create(['name' => 'encuestas.ver_informe'])->syncRoles([$rol1]);
 
         // FORMULARIO
         Permission::create(['name' => 'formulario'])->syncRoles([$rol1]);
         Permission::create(['name' => 'formulario.inicio'])->syncRoles([$rol1]);
         Permission::create(['name' => 'formulario.crear'])->syncRoles([$rol1]);
         Permission::create(['name' => 'formulario.eliminar'])->syncRoles([$rol1]);
         Permission::create(['name' => 'formulario.editar'])->syncRoles([$rol1]);
         Permission::create(['name' => 'formulario.estado'])->syncRoles([$rol1]);
         Permission::create(['name' => 'formulario.responer_formulario'])->syncRoles([$rol1]);
         Permission::create(['name' => 'formulario.ver_respuestas'])->syncRoles([$rol1]);

        //  DISTRITO Y COMUNIDAD
         Permission::create(['name' => 'distrito_comunidad'])->syncRoles([$rol1]);
         Permission::create(['name' => 'distrito_comunidad.inicio'])->syncRoles([$rol1]);
         Permission::create(['name' => 'distrito_comunidad.distrito.crear'])->syncRoles([$rol1]);
         Permission::create(['name' => 'distrito_comunidad.distrito.editar'])->syncRoles([$rol1]);
         Permission::create(['name' => 'distrito_comunidad.distrito.eliminar'])->syncRoles([$rol1]);


         Permission::create(['name' => 'distrito_comunidad.comunidad.crear'])->syncRoles([$rol1]);
         Permission::create(['name' => 'distrito_comunidad.comunidad.editar'])->syncRoles([$rol1]);
         Permission::create(['name' => 'distrito_comunidad.comunidad.eliminar'])->syncRoles([$rol1]);



    }
}
