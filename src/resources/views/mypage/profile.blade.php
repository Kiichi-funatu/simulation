@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<main class="register-main">
    <div class="profile-edit-container">

    <h2 class="title">プロフィール設定</h2>

    <form action="{{ route('mypage.update') }}" method="POST" enctype="multipart/form-data" class="register-form">
        @csrf

        {{-- プロフィール画像 --}}
        <div class="image-area">
            <img id="preview" 
                 src="{{ $user->profile_image ? asset('storage/'.$user->profile_image) : '/default.png' }}" 
                 class="profile-image">

            <label class="image-select-btn">
                画像を選択する
                <input type="file" name="profile_image" id="imageInput" hidden>
            </label>

            @error('profile_image')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
        

            <div class="form-group">
                <label class="form-label">ユーザー名</label>
                <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}">
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">郵便番号</label>
                <input type="text" name="postal_code" class="form-input" value="{{ old('postal_code', $user->postal_code) }}">
                @error('postal_code')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">住所</label>
                <input type="text" name="address" class="form-input" value="{{ old('address', $user->address) }}">
                @error('address')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">建物名</label>
                <input type="text" name="building" class="form-input" value="{{ old('building', $user->building) }}">
                @error('building')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-button-area">
                <button type="submit" class="submit-button">更新する</button>
            </div>

    </form>

</div>

</main>

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


@endsection
