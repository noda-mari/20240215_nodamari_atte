@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('nav')
    <div class="header__nav">
        <nav>
            <ul>
                <li><a href="/">ホーム</a></li>
                <li><a href="/user-list">従業員一覧</a></li>
                <li>
                    <a href="/user-work-list">勤務時間一覧</a>
                </li>
                <li>
                    <form action="/attendance" method="post">
                        @csrf
                        <input type="hidden" name="date" value="{{ date('Y-m-d') }}">
                        <button type="submit">日付一覧</button>
                    </form>
                </li>
                <li>
                    <form action="/logout" method="post">
                        @csrf
                        <button>ログアウト</button>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
@endsection

@section('content')
    <div class="body">
        <div class="content__header">
            <h2>{{ $user['name'] . 'さんお疲れ様です！' }}</h2>
        </div>
        <div class="working-time__box">
            <form class="working__form" action="/work-start" method="get">
                @csrf
                <div class="working-start__button">
                    <button type="submit" {{ $work_start ? '' : 'disabled' }}>勤務開始</button>
                </div>
            </form>
            <form class="working__form" action="/work-end" method="get">
                @csrf
                <div class="working-end__button">
                    <button type="submit" {{ $work_end ? '' : 'disabled' }}>勤務終了</button>
                </div>
            </form>
        </div>
        <div class="breaking-time__box">
            <form class="breaking__form" action="/break-start" method="get">
                @csrf
                <div class="breaking-start__button">
                    <button type="submit" {{ $breaking_start ? '' : 'disabled' }}>休憩開始</button>
                </div>
            </form>
            <form class="breaking__form" action="/break-end" method="get">
                <div class="breaking-end__button">
                    <button type="submit" {{ $breaking_end ? '' : 'disabled' }}>休憩終了</button>
                </div>
            </form>
        </div>
    </div>
@endsection
