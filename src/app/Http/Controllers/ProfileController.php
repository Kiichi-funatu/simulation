<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;

class ProfileController extends Controller
{
    // プロフィール表示
    public function index()
    {
        $user = Auth::user();

        // 出品した商品一覧
        $sellingItems = $user->items()->latest()->get();

        // 購入した商品一覧
        $purchasedItems = $user->purchases()
            ->with('item')
            ->latest()
            ->get()
            ->pluck('item');

        return view('mypage.profile_2', compact('user', 'sellingItems', 'purchasedItems'));
    }

    // プロフィール編集画面
    public function edit()
    {
        $user = Auth::user();
        return view('mypage.profile', compact('user'));
    }

    public function update(ProfileRequest $request)
{
    $user = Auth::user();

    // 画像アップロード処理
    if ($request->hasFile('profile_image')) {

        // 古い画像があれば削除（任意だが実務では必須）
        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }

        // 新しい画像を保存
        $path = $request->file('profile_image')->store('profile', 'public');
        $user->profile_image = $path;
    }
    
    // 画像が送信されている場合のみ処理
    /* if ($request->hasFile('profile_image')) {
        $path = $request->file('profile_image')->store('profile', 'public');
        $user->profile_image = $path;
    } */

    // その他の項目更新
    $user->name = $request->name;
    $user->postal_code = $request->postal_code;
    $user->address = $request->address;
    $user->building = $request->building;

    $user->save();
    
    // ★ここを変更★mypage.indexが見つかりませんのエラーがでたので
    return redirect('/')->with('success', 'プロフィールを更新しました');
    
    //return redirect()->route('mypage')->with('success', 'プロフィールを更新しました');
}

}
