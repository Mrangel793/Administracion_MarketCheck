<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rol::create([
            'id' => 1,
            'nombre'=> "Super Administrador",
            'descripcion'=>"Tiene todos los permisos del sistema.",
        ]);
        Rol::create([
            'id' => 2,
            'nombre'=> "Administrador",
            'descripcion'=>"Puede realizar todo el trabajo administrativo y de usuario.",
            ]);
        Rol::create([
            'id' => 3,
            'nombre'=> "Trabajador",
            'descripcion'=>"Funciones limitadas",
            ]);
        Rol::create([
            'id' => 4,
            'nombre'=> "Cliente",
            'descripcion'=>"Solo acceso a la APP",
            ]);
    }
    }

