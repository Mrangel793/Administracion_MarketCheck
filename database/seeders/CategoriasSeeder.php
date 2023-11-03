<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Categoria::create([
            'id' => 1,
            'nombre' => 'Bebidas',
            'imagen' => 'images/bebidas.jpg',
        ]);

        Categoria::create([
            'id' => 2,
            'nombre' => 'Alimentos frescos',
            'imagen' => 'images/alimentos_frescos.jpg',
        ]);

        Categoria::create([
            'id' => 3,
            'nombre' => 'Alimentos enlatados y envasados',
            'imagen' => 'images/alimentos_enlatados.jpg',
        ]);

        Categoria::create([
            'id' => 4,
            'nombre' => 'Productos secos y envasados',
            'imagen' => 'images/productos_secos.jpg',
        ]);

        Categoria::create([
            'id' => 5,
            'nombre' => 'Cuidado personal y limpieza del hogar',
            'imagen' => 'images/cuidado_personal.jpg',
        ]);

        Categoria::create([
            'id' => 6,
            'nombre' => 'Snacks y golosinas',
            'imagen' => 'images/snacks.jpg',
        ]);

        Categoria::create([
            'id' => 7,
            'nombre' => 'Cuidado del bebé',
            'imagen' => 'images/cuidado_bebe.jpg',
        ]);

        Categoria::create([
            'id' => 8,
            'nombre' => 'Productos para mascotas',
            'imagen' => 'images/productos_mascotas.jpg',
        ]);

        
    }
}
