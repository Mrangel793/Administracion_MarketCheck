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
            'nombre' => 'Alimentos básicos',
            'imagen' => 'https://ejemplo.com/imagen/alimentos-basicos.jpg',
        ]);
        
        Categoria::create([
            'id' => 2,
            'nombre' => 'Bebidas',
            'imagen' => 'https://ejemplo.com/imagen/bebidas.jpg',
        ]);
        
        Categoria::create([
            'id' => 3,
            'nombre' => 'Productos enlatados y secos',
            'imagen' => 'https://ejemplo.com/imagen/enlatados-y-secos.jpg',
        ]);
        
        Categoria::create([
            'id' => 4,
            'nombre' => 'Snacks y golosinas',
            'imagen' => 'https://ejemplo.com/imagen/snacks-y-golosinas.jpg',
        ]);
        
        Categoria::create([
            'id' => 5,
            'nombre' => 'Productos de despensa',
            'imagen' => 'https://ejemplo.com/imagen/despensa.jpg',
        ]);
        
        Categoria::create([
            'id' => 6,
            'nombre' => 'Productos congelados',
            'imagen' => 'https://ejemplo.com/imagen/congelados.jpg',
        ]);
        
        Categoria::create([
            'id' => 7,
            'nombre' => 'Desayuno y cereales',
            'imagen' => 'https://ejemplo.com/imagen/desayuno-y-cereales.jpg',
        ]);
        
        Categoria::create([
            'id' => 8,
            'nombre' => 'Alimentos étnicos',
            'imagen' => 'https://ejemplo.com/imagen/alimentos-etnicos.jpg',
        ]);
        
        Categoria::create([
            'id' => 9,
            'nombre' => 'Alimentos para bebés y productos infantiles',
            'imagen' => 'https://ejemplo.com/imagen/alimentos-para-bebes.jpg',
        ]);
        
        Categoria::create([
            'id' => 10,
            'nombre' => 'Productos dietéticos y saludables',
            'imagen' => 'https://ejemplo.com/imagen/dieteticos-y-saludables.jpg',
        ]);

        
    }
}
