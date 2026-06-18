<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;
use App\Models\User;
use App\Notifications\CustomVerifyEmail;

class EmailVerigicationTest extends TestCase
{
    use RefreshDatabase;

    // 会員登録後、認証メールが送信される
    /** @test */
    public function user_receives_verification_email_after_register()
    {
        // 通知をフェイク
        Notification::fake();

        // 会員登録（Fortify の登録処理）
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        // ユーザーが作成されていること
        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);

        // CustomVerifyEmail が送信されたことを確認
        Notification::assertSentTo(
            [$user],
            CustomVerifyEmail::class
            );
    }

    // メール認証サイトのメール認証を完了すると、プロフィール設定画面に遷移する
    /** @test */
    public function email_verification_redirects_to_profile_page()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        $response->assertRedirect('/mypage/profile?verified=1');

        $this->assertNotNull($user->fresh()->email_verified_at);
    }
}
