@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="body">
    <div class="login__form">
        <div class="form__header">
            <p>ログイン</p>
        </div>
        <form class="form" action="/login" method="post">
            @csrf
            <div class="error">
                @error('email')
                    {{ $message }}
                @enderror
            </div>
            <div class="form__item">
                <input type="email" name="email" placeholder="メールアドレス" value="{{ old('email') }}">
            </div>
            <div class="error">
                @error('password')
                    {{ $message }}
                @enderror
            </div>
            <div class="form__item">
                <input type="password" name="password" placeholder="パスワード">
            </div>
            <div class="form__item">
                <button type="submit">ログイン</button>
            </div>
        </form>
        <div class="register__link">
            <p>アカウントをお持ちでない方はこちらから</p>
            <a href="/register">会員登録</a>
        </div>
    </div>
</div>
@endsection


