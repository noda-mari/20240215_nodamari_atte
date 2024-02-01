@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<div class="body">
    <div class="register__form">
        <div class="form__header">
            <p>会員登録</p>
        </div>
        <form class="form" action="/register" method="post">
            @csrf
            <div class="error">
                @error('name')
                    {{ $message }}
                @enderror
            </div>
            <div class="form__item">
                <input type="text" name="name" placeholder="名前" value="{{ old('name') }}">
            </div>
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
            <div class="error">
                @error('password_confirmation')
                    {{ $message }}
                @enderror
            </div>
            <div class="form__item">
                <input type="password" name="password_confirmation" placeholder="確認用パスワード">
            </div>
            <div class="form__item">
                <button type="submit">会員登録</button>
            </div>
        </form>
        <div class="login__link">
            <p>アカウントをお持ちの方はこちらから</p>
            <a href="/login">ログイン</a>
        </div>
    </div>
</div>
@endsection


