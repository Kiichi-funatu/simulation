<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class MyListTest extends TestCase
{
    use RefreshDatabase;

    // いいねした商品だけが表示される
    /** @test */
    public function test_mylist_shows_only_liked_items()
    {
        $user = User::factory()->create([
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル101',
        ]);


        // いいねした商品
        $LikedItem = Item::factory()->create(['user_id' => User::factory()->create()->id]);
        $user->favorites()->attach($LikedItem->id);

        // いいねしていない商品
        $notLikedItem = Item::factory()->create(['user_id' => User::factory()->create()->id]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        // いいねした商品は表示される
        $response->assertSee($LikedItem->name);

        // いいねしていない商品は表示されない
        $response->assertDontSee($notLikedItem->name);

    }

    // 購入済み商品は「Sold」と表示される
    /** @test */
    public function test_mylist_show_sold_label_for_purchased_items()
    {
        $user = User::factory()->create([
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル101',
        ]);


        // いいねした商品
        $item = Item::factory()->create();
        $user->favorites()->attach($item->id);

        // 購入済みにする
        Purchase::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        // Sold が表示される
        $response->assertSee('Sold');
    }

    // 未認証の場合は何も表示されない
    /** @test */
    public function test_guest_cannot_see_mylist()
    {
        $response = $this->get('/?tab=mylist');

        // items が空であること
        $response->assertViewHas('items', function($items) {
            return $items->isEmpty();
        });
    }

    // 検索状態がマイリストでも保持される
    /** @test */
    public function test_search_keyword_is_kept_in_mylist()
    {
        $user = User::factory()->create([
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル101',
        ]);


        // いいねした商品
        $item = Item::factory()->create(['name' => 'ノートPC']);
        $user->favorites()->attach($item->id);

        // ログインして検索
        $response = $this->actingAs($user)->get('/?keyword=ノート');

        // マイリストへ遷移
        $response = $this->actingAs($user)->get('/?tab=mylist&keyword=ノート');

        // 検索キーワードが保持されている
        $response->assertSee('ノート');
    }
}
