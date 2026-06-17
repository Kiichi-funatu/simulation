<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function test_login_validate_email_required()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください'
        ]);
    }
    
    public function test_login_validate_password_required()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください'
        ]);
    }

    public function test_login_validate_invalid_credentials()
    {
        // まず正しいユーザーを作成
        \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        // 間違ったパスワードでログインを試す
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        // 認証失敗 -> email にエラーが返る
        $response->assertSessionHasErrors([
            'email' => 'ログイン情報が登録されていません'
        ]);
    }

    public function test_login_successfully()
    {
        // 事前にユーザーを作成
        \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        // 正しい情報でログイン
        $response = $this->post('login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        // ログイン成功 -> 遷移先を確認
        $response->assertRedirect('/');

        // 認証されていることを確認
        $this->assertAuthenticated();
    }
}
