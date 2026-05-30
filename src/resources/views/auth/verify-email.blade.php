@extends('layouts.app2')

@section('title', 'メール認証')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
@endsection

@section('content')
<div class="verify-wrapper">

    {{-- メッセージとボタン（中央寄せ） --}}
    <div class="verify-content">

        <p class="verify-message">
            登録していただいたメールアドレスに認証メールを送付しました。<br>
            メール認証を完了してください。
        </p>

        <a href="#" class="verify-button">
            認証はこちらから
        </a>

        <form method="POST" action="{{ route('verification.send') }}" class="resend-form">
            @csrf
            <button type="submit" class="resend-link">
                認証メールを再送する
            </button>
        </form>

    </div>

</div>
@endsection
