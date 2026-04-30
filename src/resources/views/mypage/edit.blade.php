@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/edit.css') }}">
@endsection

@section('content')
<div class="profile-edit-container">

    <h2 class="title">プロフィール設定</h2>

    <form action="{{ route('mypage.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- プロフィール画像 --}}
        <div class="image-area">
            <img id="preview" 
                 src="{{ $user->profile_image ? asset('storage/'.$user->profile_image) : '/default.png' }}" 
                 class="profile-image">

            <label class="image-select-btn">
                画像を選択
                <input type="file" name="profile_image" id="imageInput" hidden>
            </label>
        </div>

        {{-- ユーザー名 --}}
        <label class="form-label">ユーザー名</label>
        <input type="text" name="name" class="form-input"
               value="{{ old('name', $user->name) }}">

        {{-- 郵便番号 --}}
        <label class="form-label">郵便番号</label>
        <input type="text" name="postal_code" class="form-input"
               value="{{ old('postal_code', $user->postal_code) }}">

        {{-- 住所 --}}
        <label class="form-label">住所</label>
        <input type="text" name="address" class="form-input"
               value="{{ old('address', $user->address) }}">

        {{-- 建物名 --}}
        <label class="form-label">建物名</label>
        <input type="text" name="building" class="form-input"
               value="{{ old('building', $user->building) }}">

        <button type="submit" class="save-btn">更新する</button>
    </form>

</div>

{{-- JS：画像プレビュー --}}
<script>
document.getElementById('imageInput').addEventListener('change', function(e){
    const file = e.target.files[0];
    if(file){
        document.getElementById('preview').src = URL.createObjectURL(file);
    }
});
</script>

@endsection
