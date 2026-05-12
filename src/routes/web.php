<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CommentController;

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

Route::middleware(['auth', 'profile.complete'])->group(function () {
    Route::get('/purchase/{id}', [PurchaseController::class, 'index'])->name('purchase.index');
});


// 🔐 ログイン必須
Route::middleware('auth')->group(function () {
    // プロフィール表示
    Route::get('/mypage', [ProfileController::class, 'index'])->name('mypage');

    // プロフィール編集画面
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('mypage.edit');

    // プロフィール更新処理
    Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('mypage.update');

    Route::post('/favorite/{id}', [FavoriteController::class, 'store'])->name('favorite.store');

    Route::delete('/favorite/{id}', [FavoriteController::class, 'destroy'])->name('favorite.destroy');

    Route::post('/items/{id}/comment', [CommentController::class, 'store'])->name('comments.store');

    Route::post('/sell', [ItemController::class, 'store'])->name('sell.store');

    Route::get('/sell', function() {
        return view('sell'); // <- 出品画面のblade
    })->name('sell');
});