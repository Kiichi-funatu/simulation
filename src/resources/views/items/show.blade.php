@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item-show.css') }}">
@endsection

@section('content')
<main class="item-detail-main">

    <div class="item-detail-container">

        {{-- 左：商品画像 --}}
        <div class="item-image-area">
            @if($item->images->isNotEmpty())
                <img src="{{ $item->images[0]->image_path }}" class="main-image">
            @else
                <img src="/noimage.png" class="main-image">
            @endif
        </div>

        {{-- 右：商品情報 --}}
        <div class="item-info-area">

            <h2 class="item-name">{{ $item->name }}</h2>

            <p class="item-brand">{{ $item->brand }}</p>

            <p class="item-price">¥{{ number_format($item->price) }} <span class="tax-text">(税込)</span></p>

            {{-- いいね・コメントアイコン --}}
            <div class="item-icons">

                {{-- いいね（ログイン時のみ操作可能） --}}
                @auth
                    @if($item->isFavoritedBy(Auth::user()))
                        {{-- いいね解除 --}}
                        <form action="{{ route('favorite.destroy', $item->id) }}" method="POST" class="favorite-form">
                            @csrf
                            @method('DELETE')
                            <button class="favorite-button liked">
                                <img src="{{ asset('images/ハートロゴ_デフォルト.png') }}" class="icon liked-icon">
                                {{ $favoriteCount }}
                            </button>
                        </form>
                    @else
                        {{-- いいね追加 --}}
                        <form action="{{ route('favorite.store', $item->id) }}" method="POST" class="favorite-form">
                            @csrf
                            <button class="favorite-button">
                                <img src="{{ asset('images/ハートロゴ_ピンク.png') }}" class="icon">
                                {{ $favoriteCount }}
                            </button>
                        </form>
                    @endif
                @endauth

                {{-- 未ログインは表示のみ --}}
                @guest
                    <span class="favorite-button">
                        <img src="{{ asset('images/ハートロゴ_デフォルト.png') }}" class="icon">
                        {{ $favoriteCount }}
                    </span>
                @endguest

                {{-- コメント数 --}}
                <span class="comment-button">
                    <img src="{{ asset('images/ふきだしロゴ.png') }}" class="icon">
                    {{ $commentCount }}
                </span>

            </div>

             {{-- 購入ボタン --}}
            <a href="{{ route('purchase.index', $item->id) }}" class="purchase-button">
                購入手続きへ
            </a>

            <h3 class="info-title">商品の情報</h3>

            <div class="item-info-table">

                <div class="info-row">
                    <span class="info-label">カテゴリー</span>
                    <span class="info-value">{{ $item->category->name }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">商品の状態</span>
                    <span class="info-value2">{{ $item->condition->name }}</span>
                </div>

            </div>

            {{-- コメント一覧 --}}
            <div class="comment-section">

                <h3>コメント（{{ $commentCount }}）</h3>

                @foreach($item->comments as $comment)
                    <div class="comment-box">

                        {{-- ユーザー情報（丸画像＋名前） --}}
                        <div class="comment-user">
                            @if($comment->user->profile_image)
                                {{-- プロフィール画像がある場合 --}}
                                <img src="{{ $comment->user->profile_image }}" class="user-icon">
                            @else
                                {{-- プロフィール画像がない場合：灰色の丸 --}}
                                <div class="user-icon default-icon"></div>
                            @endif

                            <span class="user-name">{{ $comment->user->name }}</span>
                        </div>

                        {{-- コメント内容（空なら placeholder 表示） --}}
                        <p class="comment-content">
                            {{ $comment->comment ?: 'こちらにコメントが入ります。' }}
                        </p>

                    </div>
                @endforeach

                {{-- コメント投稿フォーム（ログイン時のみ） --}}
                @auth
                    <form action="{{ route('comments.store', $item->id) }}" method="POST" class="comment-form">
                        @csrf
                        <textarea name="comment" class="comment-input" >{{ old('comment') }}</textarea>

                        @error('comment')
                            <p class="error-message">{{ $message }}</p>
                        @enderror

                        <button type="submit" class="comment-submit">コメントを送信する</button>
                    </form>
                @endauth

            </div>
           
        </div>
    </div>

    

</main>
@endsection
