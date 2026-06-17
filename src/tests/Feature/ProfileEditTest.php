<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class ProfileEditTest extends TestCase
{
    use RefreshDatabase;

    //変更項目が初期値として過去設定されていること（プロフィール画像、ユーザー名、郵便番号、住所）
    /** @test */
    public function profile_edit_page_displays_initial_values()
    {
        // 1. 初期設定を持つユーザー
        $user = User::factory()->create([
            'name' => '初期ユーザー名',
            'profile_image' => 'initial_profile.jpg',
            'postal_code' => '123-4567',
            'address' => '熊本県阿蘇市1-1-1',
            'building' => '阿蘇ビル101',
        ]);

        // 2. ログインして編集ページ
        $response = $this->actingAs($user)->get('/mypage/profile');

        // 3. 初期値が正しく表示されてることを確認
        $response->assertSee('初期ユーザー名');
        $response->assertSee('initial_profile.jpg');
        $response->assertSee('123-4567');
        $response->assertSee('熊本県阿蘇市1-1-1');
        $response->assertSee('阿蘇ビル101');
    }
}
