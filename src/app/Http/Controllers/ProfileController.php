<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // プロフィール表示
    public function index()
    {
        $user = Auth::user();
        return view('mypage.index', compact('user'));
    }

    // プロフィール編集画面
    public function edit()
    {
        $user = Auth::user();
        return view('mypage.profile', compact('user'));
    }

    // プロフィール更新処理
    public function update(Request $request)
    {
        $user = Auth::user(); // ← ログイン中のユーザー

        $user->update([
            'name' => $request->name,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ]);

        return redirect()->route('mypage')->with('success', 'プロフィールを更新しました');
    }
}
