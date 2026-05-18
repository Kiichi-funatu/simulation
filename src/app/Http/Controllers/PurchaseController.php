<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Purchase;
use App\Http\Requests\PurchaseRequest;

class PurchaseController extends Controller
{
    /**
     * 購入画面表示（FN021）
     */
    public function purchase($id)
    {
        $item = Item::findOrFail($id);
        $user = Auth::user();

        // ★ 自分の商品は購入不可
        if ($item->user_id === $user->id) {
            return redirect()->route('items.index')->with('error', '自分の商品は購入できません。');
        }
        
        // セッションに item_id を保存（戻る時用）
        session()->put('current_item_id', $id);

        return view('purchase.index', compact('item', 'user'));
    }

    /**
     * 支払い方法選択 → checkout（FN023）
     */
    public function checkout(PurchaseRequest $request, $id)
    {
        $item = Item::findOrFail($id);

        // バリデーション
        $request->validate([
            'payment_method' => 'required|in:コンビニ払い,カード支払い',
        ]);

        // セッションに保存
        session()->put('payment_method', $request->payment_method);
        session()->save(); // ← 重要

        // buy へリダイレクト（GET）
        return redirect()->route('purchase.buy', $item->id);
    }

    /**
     * 購入確定（FN022）
     */
    public function buy(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        $user = Auth::user();

        // すでに売れていたら弾く
        if ($item->is_sold) {
            return redirect()->route('items.index')->with('error', 'この商品はすでに購入されています。');
        }

        // セッションから取得（checkout で保存済み）
        $paymentMethod = session('payment_method');

        // 念のためデフォルト
        if (!$paymentMethod) {
            $paymentMethod = 'コンビニ払い';
        }

        // 購入レコード作成（DB のカラムに合わせて記述）
        Purchase::create([
            'user_id'        => $user->id,
            'item_id'        => $item->id,
            'payment_method' => $paymentMethod,
            'price'          => $item->price,
            'postal_code'    => $user->postal_code,
            'address'        => $user->address,
            'building'       => $user->building,
        ]);

        // 商品を sold に
        $item->update(['is_sold' => true]);

        // セッション後片付け
        session()->forget(['payment_method', 'current_item_id']);

        return redirect()->route('items.index')->with('success', '購入が完了しました。');
    }
}
