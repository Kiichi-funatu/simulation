<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Favorite;

class FavoriteFactory extends Factory
{
    protected $model = Favorite::class;    

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'item_id' => \App\Models\Item::factory(),
        ];
    }
}
