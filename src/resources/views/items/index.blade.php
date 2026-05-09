@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items.css') }}">
@endsection

@section('content')
<main class="item-list-main">

    <h2 class="page-title">商品一覧</h2>

    <div class="item-grid">

        @foreach($items as $item)
            <a href="{{ route('items.show', $item->id) }}" class="item-card">

                <div class="item-image-area">

                    {{-- 画像がある場合 --}}
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

</main>
@endsection
