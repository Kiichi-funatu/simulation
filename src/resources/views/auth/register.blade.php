<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
    <title>coachtech</title>
</head>
<body>
    <header class="header">
        <img src="{{ asset('images/COACHTECHヘッダーロゴ.png') }}" alt="" class="header-logo">
    </header>

    <main class="register-main">

        <h1 class="register-title">会員登録</h1>

        <form action="/register" method="post" class="register-form">
            @csrf

            <div class="form-group">
                <label class="form-label">ユーザー名</label>
                <input type="text" name="name" class="form-input" value="{{ old('name') }}">
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">メールアドレス</label>
                <input type="email" name="email" class="form-input" value="{{ old('email') }}">
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

            <div class="form-group">
                <label class="form-label">確認用パスワード</label>
                <input type="password" name="password_confirmation" class="form-input">
                @error('password_confirmation')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-button-area">
                <button type="submit" class="submit-button">登録する</button>
            </div>
        </form>

        <div class="login-link-area">
            <a href="/login" class="login-link">ログインはこちら</a>
        </div>
        <form method="POST" action="/logout">
    @csrf
    <button>ログアウト</button>
</form>


    </main>
</body>
</html>