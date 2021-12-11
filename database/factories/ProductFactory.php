<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'image' => $this->faker->randomElement(['product_1.jpg', 'product_2.jpg', 'product_3.jpg', 'product_4.png']),
            'quantity' => $this->faker->numberBetween(1, 100),
            'expiration_date' => $this->faker->dateTimeBetween('+60 days', '+120 days'),
            'price' => $this->faker->numberBetween(10,1000),
            'contact_info' => $this->faker->phoneNumber,
            'user_id' => User::all()->random()->id,
        ];
    }
}
