<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureProfileIsComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        
        //未ログインならスルー
        if(!$user) {
            return $next($request);
        }

        //プロフィール未設定なら強制遷移
        if (is_null($user->address)) {
            //ただし、プロフィール設定画面にいる時はスルー
            if (!$request->is('mypage/profile')) {
                return redirect('/mypage/profile');
            }
        }
        
        return $next($request);
    }
}
