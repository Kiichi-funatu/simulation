<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function index() {
        // 商品一覧を新着順で取得
        $item = Item::with('image')->latest()->get();

        return view('items.index', compact('items'));
    }
}
