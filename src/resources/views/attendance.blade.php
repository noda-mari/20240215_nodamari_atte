@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('nav')
    <div class="header__nav">
        <nav>
            <ul>
                <li><a href="/">ホーム</a></li>
                <form action="/attendance" method="post">
                    @csrf
                    <input type="hidden" name="date" value="{{ date('Y-m-d') }}">
                    <button type="submit">日付一覧</button>
                </form>
                <form action="/logout" method="post">
                    @csrf
                    <button type="submit">ログアウト</button>
                </form>
                </li>
            </ul>
        </nav>
    </div>
@endsection

@section('content')
    <div class="body">
        <div class="content__header">
            <form action="/attendance" method="post">
                @csrf
                <input type="hidden" name="date_sub" value="{{ $date }}">
                <button class="back__date-button" type="submit">&lt;</button>
            </form>
            <p>{{ $date }}</p>
            <form action="/attendance" method="post">
                @csrf
                <input type="hidden" name="date_add" value="{{ $date }}">
                <button class="next__date-button" type="submit">&gt;</button>
            </form>
        </div>

        <div class="attendance-table">
            <table>
                <tr class="th-row">
                    <th>名前</th>
                    <th>勤務開始</th>
                    <th>勤務終了</th>
                    <th>休憩時間</th>
                    <th>勤務時間</th>
                </tr>
                @foreach ($attendances as $attendance)
                    <tr class="td-row">
                        <td>{{ $attendance['user'] }}</td>
                        <td>{{ $attendance['work_start'] }}</td>
                        <td>{{ $attendance['work_end'] }}</td>
                        <td>{{ $attendance['breaking_total'] }}</td>
                        <td>{{ $attendance['worked_time'] }}</td>
                    </tr>
                @endforeach
            </table>
            {{ $attendances->appends(['date' => $date])->links() }}
        </div>
    </div>
@endsection
