<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <title>coachtech</title>
</head>
<body>
    <header class="header">
        <img src="{{ asset('images/COACHTECHヘッダーロゴ.png') }}" alt="" class="header-logo">
    </header>

    <main class="register-main">

        <h1 class="register-title">ログイン</h1>

        <form action="{{ route('login') }}" method="post" class="register-form">
            @csrf

            <div class="form-group">
                <label class="form-label">メールアドレス</label>
                <input type="email" name="email" class="form-input" value="{{ old('email') }}" autofocus>
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">パスワード</label>
                <input type="password" name="password" class="form-input">
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-button-area">
                <button type="submit" class="submit-button">ログインする</button>
            </div>
        </form>

        <div class="login-link-area">
            <a href="/register" class="login-link">会員登録はこちら</a>
        </div>

    </main>
</body>
</html>
