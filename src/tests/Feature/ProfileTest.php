<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    // 必要な情報が取得できる（プロフィール画像、ユーザー名、出品した商品一覧、購入した商品一覧）
    /** @test */
    public function profile_page_displays_user_info_and_items()
    {
        // 1. ユーザー作成
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'profile_image' => 'test_profile.jpg',
            'postal_code' => '123-4567',
            'address' => '熊本県八代市1-1-1',
            'building' => 'テストビル101',
        ]);

        // 出品した商品
        $sellItem = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '出品商品A',
        ]);
        
        // 購入した商品
        $buyItem = Item::factory()->create([
            'user_id' => User::factory()->create()->id,
            'name' => '購入商品B',
        ]);

        purchase::factory()->create([
            'user_id' => $user->id,
            'item_id' => $buyItem->id,
        ]);

        // 2. ログインしてプロフィールページへ
        $response = $this->actingAs($user)->get('/mypage');

        // プロフィール画像
        $response->assertSee('test_profile.jpg');

        // ユーザー名
        $response->assertSee('テストユーザー');

        // 出品した商品一覧
        $response->assertSee('出品商品A');

        // 購入した商品一覧
        $response->assertSee('購入商品B');
    }
}
