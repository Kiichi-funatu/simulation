<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\EmailVerificationResponse as EmailVerificationResponseContract;

class EmailVerificationResponse implements EmailVerificationResponseContract
{
    public function toResponse($request)
    {
        // メール認証完了後に飛ばしたい場所
        return redirect()->route('mypage.edit'); // 例：プロフィール設定画面
    }
}
