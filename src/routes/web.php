<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CommentController;
use App\Models\Category;
use App\Models\Condition;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/register', [RegisterController::class, 'store']);
Route::get('/register', function () {
    return view('auth.register');
});

Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('/items/{id}', [ItemController::class, 'show'])->name('items.show');

// ============================
//  ログイン必須 + プロフィール登録必須
//  → 購入に必要な情報が揃っている前提
// ============================

Route::middleware(['auth', 'profile.complete'])->group(function () {
    // ============================
    //  FN021〜FN024 購入系ルート
    // ============================

    // 購入画面表示（FN021）
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'purchase'])
        ->name('purchase.index');

    // 支払い方法選択 → Stripe or コンビニ（FN023）
    Route::post('/purchase/{item_id}/checkout', [PurchaseController::class, 'checkout'])
        ->name('purchase.checkout');

    // 購入確定（FN022）
    Route::get('/purchase/{item_id}/buy', [PurchaseController::class, 'buy'])
        ->name('purchase.buy');

    // 配送先変更（FN024）
    Route::get('/purchase/address/{item_id}', [ProfileController::class, 'editAddress'])
        ->name('purchase.address');

    Route::post('/purchase/address/{item_id}', [ProfileController::class, 'updateAddress'])
        ->name('purchase.address.update');
});


// 🔐 ログイン必須
Route::middleware('auth')->group(function () {
    

    // プロフィール表示
    Route::get('/mypage', [ProfileController::class, 'index'])->name('mypage');

    // プロフィール編集画面
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('mypage.edit');

    // プロフィール更新処理
    Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('mypage.update');

    // ============================
    //  お気に入り
    // ============================
    Route::post('/favorite/{id}', [FavoriteController::class, 'store'])->name('favorite.store');

    Route::delete('/favorite/{id}', [FavoriteController::class, 'destroy'])->name('favorite.destroy');

    // ============================
    //  コメント
    // ============================
    Route::post('/items/{id}/comment', [CommentController::class, 'store'])->name('comments.store');
    
    // ============================
    //  出品
    // ============================
    Route::post('/sell', [ItemController::class, 'store'])->name('sell.store');

    Route::get('/sell', function() {
        $categories = Category::all();
        $conditions = Condition::all();
        return view('sell', compact('categories', 'conditions')); // <- 出品画面のblade
    })->name('sell');
});