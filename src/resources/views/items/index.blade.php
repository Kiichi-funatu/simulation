

@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items.css') }}">
@endsection

@section('content')
<main class="item-list-main">

    {{-- タブ切り替え --}}
    <div class="tabs">
        <a href="/?tab=items{{ request('keyword') ? '&keyword='.request('keyword') : '' }}"
           class="tab-link {{ $tab === 'items' ? 'active' : '' }}">
            おすすめ
        </a>

        <a href="/?tab=mylist{{ request('keyword') ? '&keyword='.request('keyword') : '' }}"
           class="tab-link {{ $tab === 'mylist' ? 'active' : '' }}">
            マイリスト
        </a>
    </div>

    <h2 class="page-title">
        {{ $tab === 'items' ? 'おすすめ' : 'マイリスト' }}
    </h2>

    {{-- 検索キーワード --}}
    @if(request('keyword'))
        <p class="search-result">「{{ request('keyword') }}」の検索結果</p>
    @endif

    {{-- 0件表示 --}}
    @if(($tab === 'items' && $items->isEmpty()) || ($tab === 'mylist' && $favorites->isEmpty()))
        <p class="no-result">該当する商品はありません。</p>
    @endif

    <div class="item-grid">

        {{-- 商品一覧 --}}
        @if($tab === 'items')
            @foreach($items as $item)
                <a href="{{ route('items.show', $item->id) }}" class="item-card">
                    <div class="item-image-area">
                        {{-- ★ Sold 表示（購入済み） --}}
                        @if($item->purchase)
                            <span class="sold-label">Sold</span>
                        @endif

                        @if($item->images->isNotEmpty())
                            <img src="{{ $item->images[0]->image_path }}" class="item-image">
                        @else
                            <img src="/noimage.png" class="item-image">
                        @endif
                    </div>
                    <p class="item-name">{{ $item->name }}</p>
                </a>
            @endforeach
        @endif

        {{-- マイリスト --}}
        @if($tab === 'mylist')
            @foreach($favorites as $favorite)
                <a href="{{ route('items.show', $favorite->item->id) }}" class="item-card">
                    <div class="item-image-area">
                        @if($favorite->item->images->isNotEmpty())
                            <img src="{{ $favorite->item->images[0]->image_path }}" class="item-image">
                        @else
                            <img src="/noimage.png" class="item-image">
                        @endif
                    </div>
                    <p class="item-name">{{ $favorite->item->name }}</p>
                </a>
            @endforeach
        @endif

    </div>

</main>
@endsection

