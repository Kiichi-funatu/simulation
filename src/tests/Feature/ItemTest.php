<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;

class ItemTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function test_all_item_are_displayed()
    {
        // 商品を3つ作成
        $items = Item::factory()->count(3)->create();

        // 商品一覧ページへアクセス
        $response = $this->get('/');

        // 3つの商品名がすべて表示されていること
        foreach ($items as $item) {
            $response->assertSee($item->name);
        }
    }

    /** @test */
    public function test_sold_label_is_displayed_for_purchased_items()
    {
        // 購入者
        $buyer = User::factory()->create();

        // 商品
        $item = Item::factory()->create();

        // 購入レコードを作成
        \App\Models\Purchase::factory()->create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
        ]);

        // 商品一覧ページへアクセス
        $response = $this->get('/');

        // sold が表示されること
        $response->assertSee('Sold');
    }

    /** @test */
    public function test_my_own_items_are_not_displayed()
    {
        // ログインユーザー
        $user = User::factory()->create();

        // 自分の商品
        $myItem = Item::factory()->create([
            'user_id' => $user->id,
            'name' => 'MY_ITEM_SHOULD_NOT_APPEAR',
        ]);

        // 他人の商品
        $otherItem = Item::factory()->create(['user_id' => User::factory()->create()->id,
        'name' => 'OTHER_ITEM_SHOULD_APPEAR',]);

        // ログイン
        $this->actingAs($user);

        // 商品一覧ページへアクセス
        $response = $this->get('/');

        // 自分の商品は表示させない
        $response->assertDontSee($myItem->name);

        // 他人の商品は表示させる
        $response->assertSee($otherItem->name);
    }

    // 商品名で部分一致検索ができる
    /** @test */
    public function test_search_items_by_partial_match()
    {
        // 部分一致する商品
        $item1 = Item::factory()->create(['name' => 'ノートPC']);
        $item2 = Item::factory()->create(['name' => 'ゲーミングノート']);

        // 一致しない商品
        $item3 = Item::factory()->create(['name' => '腕時計']);

        $response = $this->get('/?keyword=ノート');

        $response->assertSee($item1->name);
        $response->assertSee($item2->name);
        $response->assertDontSee($item3->name);

    }
}
