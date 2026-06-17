<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;

class CommentTest extends TestCase
{
    use RefreshDatabase;
    
    // ログイン済みのユーザーはコメントを送信できる
    /** @test */
    public function login_in_user_can_send_comment()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => User::factory()->create()->id
        ]);

        $response = $this->actingAs($user)->post("/items/{$item->id}/comment", [
            'comment' => 'いい商品ですね'
        ]);

        // DB に保存されているか
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'いい商品ですね',
        ]);

        // コメント数が増えているか
        $this->assertEquals(1, Comment::where('item_id', $item->id)->count());
    }

    // ログイン前のユーザーはコメントを送信できない
    /** @test */
    public function guest_cannot_send_comment()
    {
        $item = Item::factory()->create([
            'user_id' => User::factory()->create()->id
        ]);

        $response = $this->post("/items/{$item->id}/comment", [
            'comment' => 'ゲストコメント'
        ]);

        // ログインページリダイレクト
        $response->assertRedirect('/login');

        // DB に保存されていない
        $this->assertDatabaseMissing('comments', [
            'comment' => 'ゲストコメント'
        ]);
    }
    
    // コメントが入力されていない場合、バリデーションメッセージが表示される
    /** @test */
    public function comment_is_required()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => User::factory()->create()->id
        ]);

        $response = $this->actingAs($user)->post("/items/{$item->id}/comment", [
            'comment' => ''
        ]);

        // バリデーションエラー
        $response->assertSessionHasErrors(['comment']);
    }

    // コメントが255字以上の場合、バリデーションメッセージが表示される
    /** @test */
    public function comment_must_be_less_than_255_characters()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => User::factory()->create()->id
        ]);

        $longText = str_repeat('あ', 256);

        $response = $this->actingAs($user)->post("/items/{$item->id}/comment", [
            'comment' => $longText
        ]);

        // バリデーションエラー
        $response->assertSessionHasErrors(['comment']);
    }
}
