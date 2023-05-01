<?php

namespace Database\Factories;

use App\Models\Products;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Products>
 */
class ProductsFactory extends Factory
{
    protected $model = Products::class;

    public function definition()
    {
        return [
            'product_name' => $this->faker->name,
            'product_description' => $this->faker->sentence,
            'product_price' => $this->faker->randomFloat(2, 0, 1000)
        ];
    }
}