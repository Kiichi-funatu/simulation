<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;

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


Route::middleware(['auth', 'profile.complete'])->group(function () {

    // プロフィール表示
    Route::get('/mypage', [ProfileController::class, 'index'])->name('mypage');

    // プロフィール編集画面
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('mypage.edit');

    // プロフィール更新処理
    Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('mypage.update');
});

Route::get('/', [ItemController::class, 'index'])->name('items.index');