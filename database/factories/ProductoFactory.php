<?php

namespace Database\Factories;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Producto>
 */
class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categorias = ['Laptops', 'Smartphones', 'Monitores', 'Periféricos', 'Audio', 'Componentes'];

        return [
            'nombre' => fake()->unique()->words(3, true),
            'descripcion' => fake()->sentence(10),
            'precio' => fake()->randomFloat(2, 50, 25000),
            'stock' => fake()->numberBetween(0, 50),
            'categoria' => fake()->randomElement($categorias),
            'estado' => fake()->boolean(85),
            'atributos' => [
                'marca' => fake()->company(),
                'color' => fake()->safeColorName(),
                'garantia' => fake()->randomElement(['1 año', '2 años', '6 meses']),
            ],
        ];
    }
}
