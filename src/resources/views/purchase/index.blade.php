@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<main class="purchase-main">

    {{-- ★★★ 購入フォーム全体 ★★★ --}}
    <form action="{{ route('purchase.checkout', $item->id) }}" method="POST" class="purchase-container">
        @csrf

        {{-- 左側 --}}
        <div class="purchase-left">

            {{-- 商品画像 + 商品名 + 価格 --}}
            <div class="item-header">

                <div class="purchase-item-image">
                    @if($item->images->isNotEmpty())
                        <img src="{{ $item->images[0]->image_path }}" class="item-image">
                    @else
                        <div class="no-image">商品画像</div>
                    @endif
                </div>

                <div class="item-info">
                    <p class="item-name">{{ $item->name }}</p>
                    <p class="item-price">¥{{ number_format($item->price) }}</p>
                </div>

            </div>

            {{-- 支払い方法 --}}
            <div class="payment-section">
                <label class="section-title">支払い方法</label>
                <select name="payment_method" class="payment-select">
                    <option value="">選択してください</option>
                    <option value="コンビニ払い">コンビニ払い</option>
                    <option value="カード支払い">カード支払い</option>
                </select>

                {{-- ★ バリデーションエラー表示 ★ --}}
                @error('payment_method')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            {{-- 配送先 --}}
            <div class="address-section">
                <label class="section-title">配送先</label>
                <div class="address-box">
                    <p>〒{{ $user->postal_code }}</p>
                    <p>{{ $user->address }}</p>
                    <a href="{{ route('purchase.address', $item->id) }}" class="address-edit">変更する</a>
                </div>
            </div>

        </div>

        {{-- 右側：サマリー --}}
        <div class="purchase-right">

            <div class="summary-box">
                <p class="summary-row">
                    <span>商品代金</span>
                    <span>¥{{ number_format($item->price) }}</span>
                </p>

                <p class="summary-row">
                    <span>支払い方法</span>
                    <span class="summary-payment">コンビニ払い</span>
                </p>

                <button type="submit" class="purchase-button">購入する</button>
            </div>

        </div>

    </form>

</main>
@endsection
