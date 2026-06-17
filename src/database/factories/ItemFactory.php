<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'price' => $this->faker->numberBetween(100, 10000),
            'description' => $this->faker->sentence(),
            'user_id' => \App\Models\User::factory(),
            'condition_id' => \App\Models\Condition::factory(),
        ];
    }
}
