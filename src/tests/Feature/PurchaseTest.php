<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;
    
    //「購入する」ボタンを押下すると購入が完了する
    /** @test */
    public function user_can_purchase_an_item()
    {
        $user = User::factory()->create([
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル101',
        ]);

        // 別のユーザーの商品
        $item = Item::factory()->create([
            'user_id' => User::factory()->create()->id
        ]);

        // 購入処理
        $response = $this->actingAs($user)->get("/purchase/{$item->id}/buy");

        // DB に購入履歴が保存されている
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    } 

    // 購入した商品は商品一覧画面にて「sold」と表示される
    /** @test */
    public function purchase_item_is_displayed_as_sold_in_item_list()
    {
        $user = User::factory()->create([
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル101',
        ]);


        $item = Item::factory()->create([
            'user_id' => User::factory()->create()->id
        ]);

        // 購入
        purchase::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // 商品一覧を表示
        $response = $this->actingAs($user)->get('/');

        // Sold 表示があるか
        $response->assertSee('Sold');
    }

    //「プロフィール/購入した商品一覧」に追加されている
    /** @test */
    public function purchase_item_is_added_to_profile_purchase_list()
    {
        $user = User::factory()->create([
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル101',
        ]);

        
        $item = Item::factory()->create([
            'user_id' => User::factory()->create()->id,
            'name' => '購入テスト商品',
        ]);

        // 購入
        purchase::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // マイページ（購入一覧）を表示
        $response = $this->actingAs($user)->get('/mypage?page=buy');

        // 購入した商品が表示されている
        $response->assertSee('購入テスト商品');
    }

    // 小計画面で変更が反映される
    /** @test */
    public function selected_payment_method_is_saved_in_purchase_record()
    {
        $user = User::factory()->create([
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル101',
        ]);

        $item = Item::factory()->create([
            'user_id' => User::factory()->create()->id,
            'price' => 3000,
        ]);

        // ログイン
        $this->actingAs($user);

        // checkout で支払い方法を選択
        $response = $this->post("/purchase/{$item->id}/checkout", [
            'payment_method' => 'カード支払い',
        ]);

        // buy にリダイレクトされる
        $response->assertRedirect("/purchase/{$item->id}/buy");

        // buy にアクセス → 購入確定処理が走る
        $this->get("/purchase/{$item->id}/buy");

        // DB に支払い方法が保存されていることを確認
        $this->assertDatabaseHas('purchases', [
            'user_id'        => $user->id,
            'item_id'        => $item->id,
            'payment_method' => 'カード支払い',
            'price'          => 3000,
        ]);
    }
}
