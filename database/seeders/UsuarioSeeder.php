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
        $usuario->nombres = 'Michael';
        $usuario->paterno = 'caceres';
        $usuario->materno = 'quina';
        $usuario->estado = 'activo';
        $usuario->email = 'rodrigo@gmail.com';
        $usuario->save();

        $usuario->syncRoles(['administrador']);


        Permission::create(['name' => 'inicio.index'])->assignRole($rol1);

        Permission::create(['name' => 'admin.index'])->syncRoles([$rol1,$rol2]);


        Permission::create(['name' => 'admin.usuario.incio'])->syncRoles([$rol1,$rol2]);
        Permission::create(['name' => 'admin.usuario.crear'])->assignRole($rol1);
        Permission::create(['name' => 'admin.usuario.desactivar'])->assignRole($rol1);
        Permission::create(['name' => 'admin.usuario.reset'])->assignRole($rol1);
    }
}
