@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/user_list.css') }}">
@endsection

@section('nav')
    <div class="header__nav">
        <nav>
            <ul>
                <li><a href="/">ホーム</a></li>
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
            <p>社員一覧</p>
        </div>

        <div class="user_list-table">
            <table>
                <tr class="th-row">
                    <th>ID</th>
                    <th>氏名</th>
                    <th>メールアドレス</th>
                </tr>
                @foreach ($users as $user)
                    <tr class="td-row">
                        <td>{{ $user['id'] }}</td>
                        <td>{{ $user['name'] }}</td>
                        <td>{{ $user['email'] }}</td>
                        <td>
                            <form class="index-form" action='/user-work-list' method="post">
                                @csrf
                                <input type="hidden" name="id" value="{{ $user['id'] }}">
                                <button class="index-button" type="submit">勤務時間一覧</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
            {{ $users->links() }}
        </div>
    </div>
@endsection
