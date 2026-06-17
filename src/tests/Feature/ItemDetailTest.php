<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Comment;
use App\Models\Favorite;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    // 必要な情報が表示される（商品画像、商品名、ブランド名、価格、いいね数、コメント数、商品説明、商品情報（カテゴリ、商品の状態）、コメント数、コメントしたユーザー情報、コメント内容）
    /** @test */
    public function test_item_detail_displays_all_required_information()
    {
        $user = User::factory()->create();

        $condition = Condition::factory()->create(['name' => '新品']);
        $category = category::factory()->create(['name' => 'バッグ']);

        $item = Item::factory()->create([
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'price' => 3000,
            'description' => '説明文',
            'condition_id' => $condition->id,
        ]);

        $item->categories()->attach($category->id);

        // いいね
        Favorite::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // コメント
        Comment::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'いい商品ですね',
        ]);

        $response = $this->get("/items/{$item->id}");

        $response->assertSee('テスト商品');
        $response->assertSee('テストブランド');
        $response->assertSee('3,000');
        $response->assertSee('説明文');
        $response->assertSee('新品');
        $response->assertSee('バッグ');

        // いいね数
        $response->assertSee('1');

        // コメント数
        $response->assertSee('1');

        // コメント内容
        $response->assertSee('いい商品ですね');
        $response->assertSee($user->name);
    }

    // 複数選択されたカテゴリが表示されているか
    /** @test */
    public function test_item_detail_displays_multiple_categories()
    {
        $item = Item::factory()->create();

        $cat1 = Category::factory()->create(['name' => '本']);
        $cat2 = Category::factory()->create(['name' => 'ゲーム']);
        $cat3 = Category::factory()->create(['name' => '家電']);

        $item->categories()->attach([$cat1->id, $cat2->id, $cat3->id,]);

        $response = $this->get("/items/{$item->id}");

        $response->assertSee('本');
        $response->assertSee('ゲーム');
        $response->assertSee('家電');
    }
}
