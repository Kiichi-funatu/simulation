<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Favorite;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    //いいねアイコンを押下することによって、いいねした商品として登録することができる。
    /** @test */
    public function user_can_favorite_an_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => User::factory()->create()->id
        ]);

        $response = $this->actingAs($user)->post("/favorite/{$item->id}");

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    // 追加済みのアイコンは色が変化する
    /** @test */
    public function favorite_icon_changes_when_favorited()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => User::factory()->create()->id
        ]);

        // いいね済みにする
        Favorite::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($user)->get("/items/{$item->id}");

        // いいね済みアイコンが表示される（赤いハート画像）
        $response->assertSee('ハートロゴ_ピンク.png');
    }

    // 再度いいねアイコンを押下することによって、いいねを解除することができる。
    /** @test */
    public function user_can_uvfavorite_an_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => User::factory()->create()->id
        ]);

        Favorite::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAS($user)->delete("favorite/{$item->id}");

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    // 未ログインユーザーはいいねできない
    /** @test */
    public function guest_cannot_favorite()
    {
        $item = Item::factory()->create([
            'user_id' => User::factory()->create()->id
        ]);

        $response = $this->post("/favorite/{$item->id}");

        $response->assertRedirect('/login');
    }
}
