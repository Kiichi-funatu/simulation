<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;


class LogoutTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function test_user_can_logout_successfully()
    {
        // ① ログイン状態を作る
        $user = User::factory()->create();
        $this->actingAs($user);

        // ② ログアウト実行
        $response = $this->post('/logout');

        // ③ ログアウト後の遷移先を確認
        $response->assertRedirect('/');

        // ④ 未ログイン状態になっていることを確認
        $this->assertGuest();
    }
}
