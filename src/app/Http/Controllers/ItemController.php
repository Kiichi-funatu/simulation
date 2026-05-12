<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Favorite;   
use Illuminate\Support\Facades\Auth; 
use App\Http\Requests\ExhibitionRequest;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'items'); // デフォルトは商品一覧

        // 商品一覧タブ
        if ($tab === 'items') {

            $query = Item::with(['images', 'condition', 'category']);

            if ($request->filled('keyword')) {
                $keyword = $request->keyword;
                $query->where('name', 'like', "%{$keyword}%");
            }

            $items = $query->orderBy('id', 'desc')->get();

            return view('items.index', [
                'tab' => 'items',
                'items' => $items,
                'favorites' => collect(), // 空で渡す
            ]);
        }

        // マイリストタブ
        if ($tab === 'mylist') {

            $query = Favorite::where('user_id', Auth::id())
                            ->with('item.images');

            if ($request->filled('keyword')) {
                $keyword = $request->keyword;

                $query->whereHas('item', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            }

            $favorites = $query->get();

            return view('items.index', [
                'tab' => 'mylist',
                'items' => collect(), // 空で渡す
                'favorites' => $favorites,
            ]);
        }
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

    public function store(ExhibitionRequest $request)
    {
        $validated = $request->validated();

        // 画像保存
        $imagePath = $request->file('image')->store('items', 'public');

        // 商品登録
        Item::create([
            'user_id'     => auth()->id(),
            'image_path'  => '/storage/' . $imagePath,
            'category'    => $validated['category'],
            'condition'   => $validated['condition'],
            'name'        => $validated['name'],
            'brand'       => $request->brand,
            'description' => $validated['description'],
            'price'       => $validated['price'],
        ]);

        return redirect()->route('mypage')->with('success', '商品を出品しました！');
    }
}
