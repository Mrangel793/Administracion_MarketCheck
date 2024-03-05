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
        Categoria::create([
            'id' => 1,
            'nombre' => 'Alimentos básicos',
            'imagen' => 'alimentosBasicos.png',
        ]);
        
        Categoria::create([
            'id' => 2,
            'nombre' => 'Bebidas',
            'imagen' => 'bebidas.png'
        ]);
        
        Categoria::create([
            'id' => 3,
            'nombre' => 'Productos enlatados y secos',
            'imagen' => 'enlatados.png',
        ]);
        
        Categoria::create([
            'id' => 4,
            'nombre' => 'Snacks y golosinas',
            'imagen' => 'golosinas.png',
        ]);
        
        Categoria::create([
            'id' => 5,
            'nombre' => 'Productos de despensa',
            'imagen' => 'despensa.png',
        ]);
        
        Categoria::create([
            'id' => 6,
            'nombre' => 'Productos congelados',
            'imagen' => 'congelados.png',
        ]);
        
        Categoria::create([
            'id' => 7,
            'nombre' => 'Desayuno y cereales',
            'imagen' => 'cereales.png',
        ]);
        
        Categoria::create([
            'id' => 8,
            'nombre' => 'Alimentos étnicos',
            'imagen' => 'internacional.png',
        ]);
        
        Categoria::create([
            'id' => 9,
            'nombre' => 'Productos para bebés',
            'imagen' => 'bebes.png',
        ]);
        
        Categoria::create([
            'id' => 10,
            'nombre' => 'Productos dietéticos y saludables',
            'imagen' => 'saludable.png',
        ]);
        
        Categoria::create([
            'id' => 11,
            'nombre' => 'Carnes',
            'imagen' => 'carnes.png',
        ]);
        
        Categoria::create([
            'id' => 12,
            'nombre' => 'Bebidas Alcohólicas',
            'imagen' => 'licor.png',
        ]);
        
        Categoria::create([
            'id' => 13,
            'nombre' => 'Lacteos y Huevos',
            'imagen' => 'lacteos.png',
        ]);
        
        Categoria::create([
            'id' => 14,
            'nombre' => 'Panadería y Repostería',
            'imagen' => 'panaderia.png',
        ]);
        
        Categoria::create([
            'id' => 15,
            'nombre' => 'Frutas y Verduras',
            'imagen' => 'frutas.png',
        ]);
        
        Categoria::create([
            'id' => 16,
            'nombre' => 'Higiene Personal',
            'imagen' => 'belleza.png',
        ]);
        
        Categoria::create([
            'id' => 17,
            'nombre' => 'Artículos de Limpieza',
            'imagen' => 'detergente.png',
        ]);
        
        Categoria::create([
            'id' => 18,
            'nombre' => 'Electrodomésticos',
            'imagen' => 'electrodomesticos.png',
        ]);
        
        Categoria::create([
            'id' => 19,
            'nombre' => 'Cuidado del Hogar',
            'imagen' => 'hogar.png',
        ]);
        
        Categoria::create([
            'id' => 20,
            'nombre' => 'Mascotas',
            'imagen' => 'mascotas.png',
        ]);
        
        Categoria::create([
            'id' => 21,
            'nombre' => 'Belleza y Cuidado Personal',
            'imagen' => 'maquillaje.png',
        ]);

        Categoria::create([
            'id' => 21,
            'nombre' => 'Productos carnicos',
            'imagen' => 'carnicos.png',
        ]);

        Categoria::create([
            'id' => 21,
            'nombre' => 'Productos de farmacia',
            'imagen' => 'farmacia.png',
        ]);
        
        
    }
}
