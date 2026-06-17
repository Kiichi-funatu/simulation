<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class PurchaseAddressTest extends TestCase
{
    use RefreshDatabase;

    // 送付先住所変更画面にて登録した住所が商品購入画面に反映されている
    /** @test */
    public function updated_address_is_reflected_in_purchase_screen()
    {
        // 1. ユーザー作成（初期住所）
        $user = User::factory()->create([
            'postal_code' => '111-1111',
            'address' => '旧住所',
            'building' => '旧ビル',
        ]);

        // 商品（別ユーザー）
        $item = Item::factory()->create([
            'user_id' => User::factory()->create()->id,
        ]);

        // ログイン
        $this->actingAs($user);

        // 2. 住所変更
        $this->post("/purchase/address/{$item->id}", [
            'postal_code' => '222-2222',
            'address' => '新住所',
            'building' => '新ビル',
        ]);

        // 3. 購入画面を再度開く
        $response = $this->get("/purchase/{$item->id}");

        // 新しい住所が反映されていること
        $response->assertSee('222-2222');
        $response->assertSee('新住所');
        $response->assertSee('新ビル');
    }

    // 購入した商品に送付先住所が紐づいて登録される

    /** @test */
    public function purchase_record_contains_updated_address()
    {
        // 1. ユーザー作成（初期住所）
        $user = User::factory()->create([
            'postal_code' => '111-1111',
            'address' => '旧住所',
            'building' => '旧ビル',
        ]);

        // 商品（別ユーザー）
        $item = Item::factory()->create([
            'user_id' => User::factory()->create()->id,
            'price' => 3000,
        ]);

        // ログイン
        $this->actingAs($user);

        // 2. 住所変更
        $this->post("/purchase/address/{$item->id}", [
            'postal_code' => '333-3333',
            'address' => '購入時住所',
            'building' => '購入時ビル',
        ]);

        // 3. 購入実行
        $this->get("/purchase/{$item->id}/buy");

        // 購入履歴に住所が保存されていること
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'postal_code' => '333-3333',
            'address' => '購入時住所',
            'building' => '購入時ビル',
        ]);
    }
}
