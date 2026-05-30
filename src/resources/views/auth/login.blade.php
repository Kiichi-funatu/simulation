@extends('layouts.app2')

@section('title', 'ログイン')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')

<div class="login-container">

    <h1 class="login-title">ログイン</h1>

    <form action="{{ route('login') }}" method="post" class="login-form">
        @csrf

        <div class="form-group">
            <label class="form-label">メールアドレス</label>
            <input type="text" name="email" class="form-input" value="{{ old('email') }}" autofocus>
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

        <button type="submit" class="submit-button">ログインする</button>
    </form>

    <div class="register-link-area">
        <a href="/register" class="register-link">会員登録はこちら</a>
    </div>

</div>

@endsection
