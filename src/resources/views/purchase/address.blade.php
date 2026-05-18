@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">

<main class="address-main">

    <div class="address-container">

        <h2 class="address-title">住所の変更</h2>

        <form action="{{ route('purchase.address.update', $item->id) }}" method="POST">
            @csrf

            {{-- 郵便番号 --}}
            <div class="form-group">
                <label for="postal_code">郵便番号</label>
                <input type="text" id="postal_code" name="postal_code"
                       value="{{ old('postal_code', $user->postal_code) }}"
                       class="form-input">
                
                {{-- ★ エラー表示 --}}
                @error('postal_code')
                    <div class="error-message">{{ $message }}</div>
                @enderror       
            </div>

            {{-- 住所 --}}
            <div class="form-group">
                <label for="address">住所</label>
                <input type="text" id="address" name="address"
                       value="{{ old('address', $user->address) }}"
                       class="form-input">

                {{-- ★ エラー表示 --}}
                @error('address')
                    <div class="error-message">{{ $message }}</div>
                @enderror       
            </div>

            {{-- 建物名 --}}
            <div class="form-group">
                <label for="building">建物名</label>
                <input type="text" id="building" name="building"
                       value="{{ old('building', $user->building) }}"
                       class="form-input">
            </div>

            <button type="submit" class="update-button">更新する</button>
        </form>

    </div>

</main>
@endsection
