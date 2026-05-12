@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items.css') }}">
<link rel="stylesheet" href="{{ asset('css/profile_2.css') }}">
@endsection

@section('content')
<main class="item-list-main">

    {{-- プロフィール --}}
    <div class="mypage-container">
        <div class="profile-header">
            <img src="{{ $user->profile_image ? asset('storage/'.$user->profile_image) : '/default.png' }}"
                 class="profile-image">

            <div class="profile-info">
                <div class="profile-row">
                    <h2 class="profile-name">{{ $user->name }}</h2>
                    <a href="{{ route('mypage.edit') }}" class="edit-btn">
                        プロフィールを編集
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- タブ（テキストタブ風） --}}
    <div class="mypage-tabs">
        <button class="mypage-tab active" data-tab="selling">出品した商品</button>
        <button class="mypage-tab" data-tab="purchased">購入した商品</button>
    </div>

    {{-- 出品した商品 --}}
    <div id="selling" class="tab-content active">
        <div class="item-grid">
            @foreach ($sellingItems as $item)
                <a href="{{ route('items.show', $item->id) }}" class="item-card">
                    <div class="item-image-area">
                        @if($item->images->isNotEmpty())
                            <img src="{{ $item->images[0]->image_path }}" class="item-image">
                        @else
                            <img src="/noimage.png" class="item-image">
                        @endif
                    </div>
                    <p class="item-name">{{ $item->name }}</p>
                </a>
            @endforeach
        </div>
    </div>

    {{-- 購入した商品 --}}
    <div id="purchased" class="tab-content">
        <div class="item-grid">
            @foreach ($purchasedItems as $item)
                <a href="{{ route('items.show', $item->id) }}" class="item-card">
                    <div class="item-image-area">
                        @if($item->images->isNotEmpty())
                            <img src="{{ $item->images[0]->image_path }}" class="item-image">
                        @else
                            <img src="/noimage.png" class="item-image">
                        @endif
                    </div>
                    <p class="item-name">{{ $item->name }}</p>
                </a>
            @endforeach
        </div>
    </div>

</main>

<script>
document.querySelectorAll('.mypage-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('.mypage-tab').forEach(t => t.classList.remove('active'));
        tab.classList.add('active');

        const target = tab.dataset.tab;
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        document.getElementById(target).classList.add('active');
    });
});
</script>
@endsection
