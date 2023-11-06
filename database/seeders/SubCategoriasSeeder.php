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
        SubCategoria::create([
            'nombre' => 'Frutas y verduras frescas',
            'imagen' => 'https://ejemplo.com/imagen/frutas-y-verduras.jpg',
            'categoria_id' => 1,
        ]);
        
        SubCategoria::create([
            'nombre' => 'Carnes y pescados frescos',
            'imagen' => 'https://ejemplo.com/imagen/carnes-y-pescados.jpg',
            'categoria_id' => 1,
        ]);
        
        SubCategoria::create([
            'nombre' => 'Productos lácteos (leche, queso, yogur)',
            'imagen' => 'https://ejemplo.com/imagen/productos-lacteos.jpg',
            'categoria_id' => 1,
        ]);
        
        SubCategoria::create([
            'nombre' => 'Pan y productos horneados',
            'imagen' => 'https://ejemplo.com/imagen/pan-y-horneados.jpg',
            'categoria_id' => 1,
        ]);
        
        SubCategoria::create([
            'nombre' => 'Huevos y productos refrigerados',
            'imagen' => 'https://ejemplo.com/imagen/huevos-y-refrigerados.jpg',
            'categoria_id' => 1,
        ]);
        
        SubCategoria::create([
            'nombre' => 'Agua embotellada',
            'imagen' => 'https://ejemplo.com/imagen/agua-embotellada.jpg',
            'categoria_id' => 2,
        ]);
        
        SubCategoria::create([
            'nombre' => 'Refrescos y bebidas gaseosas',
            'imagen' => 'https://ejemplo.com/imagen/refrescos-y-gaseosas.jpg',
            'categoria_id' => 2,
        ]);
        
        SubCategoria::create([
            'nombre' => 'Jugos y bebidas de frutas',
            'imagen' => 'https://ejemplo.com/imagen/jugos-y-bebidas-de-frutas.jpg',
            'categoria_id' => 2,
        ]);
        
        SubCategoria::create([
            'nombre' => 'Bebidas alcohólicas (cerveza, vino, licores)',
            'imagen' => 'https://ejemplo.com/imagen/bebidas-alcoholicas.jpg',
            'categoria_id' => 2,
        ]);
        
        SubCategoria::create([
            'nombre' => 'Conservas (vegetales, frutas, carnes enlatadas)',
            'imagen' => 'https://ejemplo.com/imagen/conservas.jpg',
            'categoria_id' => 3,
        ]);
        
        SubCategoria::create([
            'nombre' => 'Pastas y arroz',
            'imagen' => 'https://ejemplo.com/imagen/pastas-y-arroz.jpg',
            'categoria_id' => 3,
        ]);
        
        SubCategoria::create([
            'nombre' => 'Sopas y caldos',
            'imagen' => 'https://ejemplo.com/imagen/sopas-y-caldos.jpg',
            'categoria_id' => 3,
        ]);
        
        SubCategoria::create([
            'nombre' => 'Cereales y granos',
            'imagen' => 'https://ejemplo.com/imagen/cereales-y-granos.jpg',
            'categoria_id' => 3,
        ]);
        
        SubCategoria::create([
            'nombre' => 'Chips y aperitivos',
            'imagen' => 'https://ejemplo.com/imagen/chips-y-aperitivos.jpg',
            'categoria_id' => 4,
        ]);
        
        SubCategoria::create([
            'nombre' => 'Dulces y chocolates',
            'imagen' => 'https://ejemplo.com/imagen/dulces-y-chocolates.jpg',
            'categoria_id' => 4,
        ]);
        
        SubCategoria::create([
            'nombre' => 'Frutos secos',
            'imagen' => 'https://ejemplo.com/imagen/frutos-secos.jpg',
            'categoria_id' => 4,
        ]);
        
        SubCategoria::create([
            'nombre' => 'Galletas y bocadillos',
            'imagen' => 'https://ejemplo.com/imagen/galletas-y-bocadillos.jpg',
            'categoria_id' => 4,
        ]);
        
        SubCategoria::create([
            'nombre' => 'Aceites y vinagres',
            'imagen' => 'https://ejemplo.com/imagen/aceites-y-vinagres.jpg',
            'categoria_id' => 5,
        ]);
        
        SubCategoria::create([
            'nombre' => 'Salsas (salsa de tomate, salsa de soja, ketchup)',
            'imagen' => 'https://ejemplo.com/imagen/salsas.jpg',
            'categoria_id' => 5,
        ]);
        
        SubCategoria::create([
            'nombre' => 'Especias y condimentos',
            'imagen' => 'https://ejemplo.com/imagen/especias-y-condimentos.jpg',
            'categoria_id' => 5,
        ]);
        
        SubCategoria::create([
            'nombre' => 'Harina y azúcar',
            'imagen' => 'https://ejemplo.com/imagen/harina-y-azucar.jpg',
            'categoria_id' => 5,
        ]);
        
        SubCategoria::create([
            'nombre' => 'Comidas congeladas (pizzas, nuggets de pollo)',
            'imagen' => 'https://ejemplo.com/imagen/comidas-congeladas.jpg',
            'categoria_id' => 6,
        ]);
        
        SubCategoria::create([
            'nombre' => 'Vegetales congelados',
            'imagen' => 'https://ejemplo.com/imagen/vegetales-congelados.jpg',
            'categoria_id' => 6,
        ]);
    
        SubCategoria::create([
            'nombre' => 'Postres congelados (helado, pasteles)',
            'imagen' => 'https://ejemplo.com/imagen/postres-congelados.jpg',
            'categoria_id' => 6,
        ]);
        
        SubCategoria::create([
            'nombre' => 'Cereales para el desayuno',
            'imagen' => 'https://ejemplo.com/imagen/cereales-desayuno.jpg',
            'categoria_id' => 7,
        ]);
        
        SubCategoria::create([
            'nombre' => 'Avena',
            'imagen' => 'https://ejemplo.com/imagen/avena.jpg',
            'categoria_id' => 7,
        ]);
        
        SubCategoria::create([
            'nombre' => 'Panqueques y siropes',
            'imagen' => 'https://ejemplo.com/imagen/panqueques-siropes.jpg',
            'categoria_id' => 7,
        ]);
        
        SubCategoria::create([
            'nombre' => 'Café y té',
            'imagen' => 'https://ejemplo.com/imagen/cafe-te.jpg',
            'categoria_id' => 7,
        ]);
        
        SubCategoria::create([
            'nombre' => 'Ingredientes y productos de cocina de diferentes culturas',
            'imagen' => 'https://ejemplo.com/imagen/alimentos-etnicos.jpg',
            'categoria_id' => 8,
        ]);
        
        SubCategoria::create([
            'nombre' => 'Alimentos para bebés envasados',
            'imagen' => 'https://ejemplo.com/imagen/alimentos-para-bebes.jpg',
            'categoria_id' => 9,
        ]);
        
        SubCategoria::create([
            'nombre' => 'Fórmula para bebés',
            'imagen' => 'https://ejemplo.com/imagen/formula-bebes.jpg',
            'categoria_id' => 9,
        ]);
        
        SubCategoria::create([
            'nombre' => 'Comida para niños',
            'imagen' => 'https://ejemplo.com/imagen/comida-ninos.jpg',
            'categoria_id' => 9,
        ]);
        
        SubCategoria::create([
            'nombre' => 'Alimentos orgánicos',
            'imagen' => 'https://ejemplo.com/imagen/alimentos-organicos.jpg',
            'categoria_id' => 10,
        ]);
        
        SubCategoria::create([
            'nombre' => 'Productos sin gluten',
            'imagen' => 'https://ejemplo.com/imagen/productos-sin-gluten.jpg',
            'categoria_id' => 10,
        ]);
        
        SubCategoria::create([
            'nombre' => 'Alimentos bajos en calorías',
            'imagen' => 'https://ejemplo.com/imagen/alimentos-bajos-calorias.jpg',
            'categoria_id' => 10,
        ]);
        
        SubCategoria::create([
            'nombre' => 'Suplementos dietéticos',
            'imagen' => 'https://ejemplo.com/imagen/suplementos-dieteticos.jpg',
            'categoria_id' => 10,
        ]);


    }
}
