<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    // いいね追加
    public function store($itemId)
    {
        Favorite::firstOrCreate([
            'user_id' => Auth::id(),
            'item_id' => $itemId,
        ]);

        return back();
    }

    // いいね解除
    public function destroy($itemId)
    {
        Favorite::where('user_id', Auth::id())
            ->where('item_id', $itemId)
            ->delete();

        return back();
    }
}
