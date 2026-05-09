<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function index() {
        // 商品一覧を新着順で取得
        /* $items = Item::with('image')->latest()->get(); */
        $items = Item::with(['images', 'condition', 'category'])
            ->orderBy('id', 'desc')
            ->get();

        return view('items.index', compact('items'));
    }

    public function show($id)
    {
        $item = Item::with([
            'images',
            'category',
            'condition',
            'user',
            'favorites',
            'comments.user'
        ])->findOrFail($id);

        $favoriteCount = $item->favorites->count();
        $commentCount = $item->comments->count();

        return view('items.show', compact('item', 'favoriteCount', 'commentCount'));
    }
}
