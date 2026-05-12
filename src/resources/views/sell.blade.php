@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<main class="item-create-main">

    <h2 class="page-title">商品の出品</h2>

    <form action="{{ route('sell.store') }}" method="POST" enctype="multipart/form-data" class="item-create-form">
        @csrf

        {{-- ① 商品画像 --}}
        <div class="form-group">
            <label class="form-label">商品画像</label>

            <div class="image-upload-box">
                <img id="preview" src="/noimage.png" class="preview-image">

                <label class="image-select-btn">
                    画像を選択する
                    <input type="file" name="image" id="imageInput" hidden>
                </label>
            </div>

            @error('image')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>


        {{-- ② 商品の詳細 --}}
        <div class="detail-section">

            <h3 class="section-title">商品の詳細</h3>

            {{-- カテゴリー（タグ型） --}}
            <div class="form-group">
                <label class="form-label">カテゴリー</label>

                <div class="category-tags">
                    @foreach ([
                        'ファッション','家電','インテリア','レディース','メンズ','コスメ',
                        '本','ゲーム','スポーツ','キッチン','ハンドメイド','アクセサリー',
                        'おもちゃ','ベビー・キッズ'
                    ] as $category)
                        <span class="category-tag" data-value="{{ $category }}">
                            {{ $category }}
                        </span>
                    @endforeach
                </div>

                <input type="hidden" name="category" id="categoryInput">

                @error('category')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            {{-- 商品の状態 --}}
            <div class="form-group">
                <label class="form-label">商品の状態</label>
                <select name="condition" class="form-select">
                    <option value="">選択してください</option>
                    <option value="良好">良好</option>
                    <option value="目立った汚れなし">目立った汚れなし</option>
                    <option value="やや傷や汚れあり">やや傷や汚れあり</option>
                    <option value="状態が悪い">状態が悪い</option>
                </select>
                
                @error('condition')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

        </div>


        {{-- ③ 商品名と説明 --}}
        <div class="detail-section">

            <h3 class="section-title">商品名と説明</h3>

            {{-- 商品名 --}}
            <div class="form-group">
                <label class="form-label">商品名</label>
                <input type="text" name="name" class="form-input" value="{{ old('name') }}">

                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            {{-- ブランド名 --}}
            <div class="form-group">
                <label class="form-label">ブランド名</label>
                <input type="text" name="brand" class="form-input" value="{{ old('brand') }}">
            </div>

            {{-- 商品説明 --}}
            <div class="form-group">
                <label class="form-label">商品の説明</label>
                <textarea name="description" class="form-textarea">{{ old('description') }}</textarea>

                @error('description')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            {{-- 販売価格 --}}
            <div class="form-group">
                <label class="form-label">販売価格</label>

                <div class="price-input-wrapper">
                    <span class="yen-mark">¥</span>
                    <input type="number" name="price" class="price-input" value="{{ old('price') }}">
                </div>

                @error('price')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

        </div>


        {{-- ④ 出品ボタン --}}
        <div class="form-button-area">
            <button type="submit" class="submit-button">出品する</button>
        </div>

    </form>

</main>


{{-- 画像プレビュー用 JS --}}
<script>
document.getElementById("imageInput").addEventListener("change", function (e) {
    const file = e.target.files[0];
    const reader = new FileReader();

    reader.onload = function (event) {
        const preview = document.getElementById("preview");
        preview.src = event.target.result;
        preview.style.display = "block";
    };

    if (file) {
        reader.readAsDataURL(file);
    }
});
</script>


{{-- カテゴリータグ選択 JS --}}
<script>
document.querySelectorAll('.category-tag').forEach(tag => {
    tag.addEventListener('click', () => {
        document.querySelectorAll('.category-tag').forEach(t => t.classList.remove('active'));
        tag.classList.add('active');
        document.getElementById('categoryInput').value = tag.dataset.value;
    });
});
</script>

@endsection
