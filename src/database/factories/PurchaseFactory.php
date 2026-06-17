<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Purchase;
use App\Models\User;
use App\Models\Item;

class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'item_id' => Item::factory(),
            'payment_method' => 'credit',
            'price' => 1000,
            'postal_code' => '123-4567',
            'address' => '熊本県熊本市2-2',
        ];
    }
}
