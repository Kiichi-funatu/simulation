<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FashionablyLate')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('css')
</head>
<body>

<header class="header">
    {{-- 左：ロゴ --}}
    <div class="header__left">
        <img src="{{ asset('images/COACHTECHヘッダーロゴ.png') }}" alt="logo" class="header-logo">
    </div>

    {{-- 中央：検索フォーム --}}
    <div class="header__center">
        <form action="/" method="GET" class="search-form">
            <input type="text" name="keyword" class="search-input" placeholder="何をお探しですか？"  value="{{ request('keyword') }}">
        </form>
    </div>

    {{-- 右：メニュー（マイページ / 出品 / ログアウト） --}}
    <div class="header__right">
        <a href="{{ route('mypage') }}" class="header-link">マイページ</a>
        
        <form action="/logout" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="header-link logout-btn">ログアウト</button>
        </form>

        <a href="{{ route('sell') }}" class="header-link-last">出品</a>
    </div>
</header>

<main class="main">
    @yield('content')
</main>

</body>
</html>
