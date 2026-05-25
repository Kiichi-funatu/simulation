<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FashionablyLate')</title>
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css">
    <link rel="stylesheet" href="{{ asset('css/app2.css') }}">
    @yield('css')
</head>
<body>

<header class="header">
    {{-- 左：ロゴ --}}
    <div class="header__left">
        <img src="{{ asset('images/COACHTECHヘッダーロゴ.png') }}" alt="logo" class="header-logo">
    </div>

    
</header>

<main class="main">
    @yield('content')
</main>

</body>
</html>
