<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubCategoria;

class SubCategoriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Subcategorías relacionadas con 'Alimentos frescos' (Categoría ID 2)
        SubCategoria::create([
            'nombre' => 'Frutas',
            'imagen' => 'images/productos_mascotas.jpg',
            'categoria_id' => 2,
        ]);

        SubCategoria::create([
            'nombre' => 'Verduras',
            'imagen' => 'images/productos_mascotas.jpg',
            'categoria_id' => 2,
        ]);

        // Subcategorías relacionadas con 'Alimentos enlatados y envasados' (Categoría ID 3)
        SubCategoria::create([
            'nombre' => 'Conservas de vegetales',
            'imagen' => 'images/productos_mascotas.jpg',
            'categoria_id' => 3,
        ]);

        // Subcategorías relacionadas con 'Productos secos y envasados' (Categoría ID 4)
        SubCategoria::create([
            'nombre' => 'Arroz, pasta y granos',
            'imagen' => 'images/productos_mascotas.jpg',
            'categoria_id' => 4,
        ]);

        SubCategoria::create([
            'nombre' => 'Cereales y avena',
            'imagen' => 'images/productos_mascotas.jpg',
            'categoria_id' => 4,
        ]);

        SubCategoria::create([
            'nombre' => 'Aceites y vinagres',
            'imagen' => 'images/productos_mascotas.jpg',
            'categoria_id' => 4,
        ]);

        // Subcategorías relacionadas con 'Bebidas' (Categoría ID 1)
        SubCategoria::create([
            'nombre' => 'Agua embotellada',
            'imagen' => 'images/productos_mascotas.jpg',
            'categoria_id' => 1,
        ]);

        SubCategoria::create([
            'nombre' => 'Refrescos',
            'imagen' => 'images/productos_mascotas.jpg',
            'categoria_id' => 1,
        ]);

        // Subcategorías relacionadas con 'Cuidado personal y limpieza del hogar' (Categoría ID 5)
        SubCategoria::create([
            'nombre' => 'Champú',
            'imagen' => 'images/productos_mascotas.jpg',
            'categoria_id' => 5,
        ]);

        SubCategoria::create([
            'nombre' => 'Jabón',
            'imagen' => 'images/productos_mascotas.jpg',
            'categoria_id' => 5,
        ]);

        SubCategoria::create([
            'nombre' => 'Pasta de dientes',
            'imagen' => 'images/productos_mascotas.jpg',
            'categoria_id' => 5,
        ]);

        // Subcategorías relacionadas con 'Snacks y golosinas' (Categoría ID 6)
        SubCategoria::create([
            'nombre' => 'Chips',
            'imagen' => 'images/productos_mascotas.jpg',
            'categoria_id' => 6,
        ]);

        SubCategoria::create([
            'nombre' => 'Galletas',
            'imagen' => 'images/productos_mascotas.jpg',
            'categoria_id' => 6,
        ]);

        SubCategoria::create([
            'nombre' => 'Chocolate',
            'imagen' => 'images/productos_mascotas.jpg',
            'categoria_id' => 6,
        ]);

        // Subcategorías relacionadas con 'Cuidado del bebé' (Categoría ID 7)
        SubCategoria::create([
            'nombre' => 'Pañales',
            'imagen' => 'images/productos_mascotas.jpg',
            'categoria_id' => 7,
        ]);

        SubCategoria::create([
            'nombre' => 'Alimentos para bebés',
            'imagen' => 'images/productos_mascotas.jpg',
            'categoria_id' => 7,
        ]);

        // Subcategorías relacionadas con 'Productos para mascotas' (Categoría ID 8)
        SubCategoria::create([
            'nombre' => 'Comida para perros',
            'imagen' => 'images/productos_mascotas.jpg',
            'categoria_id' => 8,
        ]);

        SubCategoria::create([
            'nombre' => 'Comida para gatos',
            'imagen' => 'images/productos_mascotas.jpg',
            'categoria_id' => 8,
        ]);


    }
}
