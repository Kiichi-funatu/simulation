<h1>プロフィール編集</h1>

<form action="{{ route('mypage.update') }}" method="POST">
    @csrf

    <div>
        <label>ユーザー名</label>
        <input type="text" name="name" value="{{ $user->name }}">
    </div>

    <div>
        <label>郵便番号</label>
        <input type="text" name="postal_code" value="{{ $user->postal_code }}">
    </div>

    <div>
        <label>住所</label>
        <input type="text" name="address" value="{{ $user->address }}">
    </div>

    <div>
        <label>建物名</label>
        <input type="text" name="building" value="{{ $user->building }}">
    </div>

    <button type="submit">更新</button>
</form>
