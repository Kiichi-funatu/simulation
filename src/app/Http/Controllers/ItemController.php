<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemImage;
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

            $query = Item::with(['images', 'condition', 'categories']);

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
            'categories',
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

        // 1. 商品を先に作成
        $item = Item::create([
            'user_id'     => auth()->id(),
            'condition_id'=> $validated['condition_id'],
            'name'        => $validated['name'],
            'brand'       => $request->brand,
            'color'       => $request->color,
            'description' => $validated['description'],
            'price'       => $validated['price'],
        ]);
        
        // 2. カテゴリを紐づける（複数）
        if ($request->filled('category_ids')) {
            $categoryIds = explode(',', $request->category_ids);
            $item->categories()->sync($categoryIds);
        }

        // 3. 画像を保存して item_images に登録
        $imagePath = $request->file('image')->store('items', 'public');

        ItemImage::create([
            'item_id'    => $item->id,
            'image_path' => '/storage/' . $imagePath,
        ]);

        return redirect()->route('mypage')->with('success', '商品を出品しました！');
    }
}
