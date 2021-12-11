<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class PriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
//            'product_id' => Product::all()->random()->id,
            'discount' => $this->faker->numberBetween(1,99),
            'date_start' => $this->faker->unique()->dateTimeBetween('+10 days', '+20 days'),
            'date_end' => $this->faker->unique()->dateTimeBetween('+21 days', '+30 days'),
        ];
    }
}
