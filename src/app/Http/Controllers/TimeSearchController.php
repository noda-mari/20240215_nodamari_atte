<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;
use DateInterval;
use App\Models\Work;
use App\Models\Breaking;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class TimeSearchController extends Controller
{
    public function index(Request $request)
    {
        // 日付の決定
        if ($request->has('date_add')) {
            $date = (new DateTime($request->input('date_add')))->modify('+1 day')->format('Y-m-d');
        } elseif ($request->has('date_sub')) {
            $date = (new DateTime($request->input('date_sub')))->modify('-1 day')->format('Y-m-d');
        } else {
            $date = $request->input('date', now()->format('Y-m-d'));
        }

        $works = Work::with('user')
            ->whereDate('start_time', $date)
            ->orWhereDate('end_time', $date)
            ->get();

        $attendances = [];

        foreach ($works as $work) {
            $user = $work->user->name ?? '不明ユーザー';
            $work_id = $work->id;
            $work_start = $work->start_time;
            $work_end = $work->end_time;

            // null チェック（未打刻など）
            if (!$work_start || !$work_end) {
                continue;
            }

            // 勤務時間の計算
            if ($work_start->format('Y-m-d') === $work_end->format('Y-m-d')) {
                $diff_time = $work_start->diff($work_end);
            } elseif ($work_start->format('Y-m-d') === $date) {
                $diff_time = $work_start->diff(new DateTime($date . ' 23:59:59'));
            } elseif ($work_end->format('Y-m-d') === $date) {
                $diff_time = (new DateTime($date . ' 00:00:00'))->diff($work_end);
            } else {
                continue;
            }

            $worked_time = new DateTime($date);
            $worked_time->add($diff_time);

            // 休憩時間取得
            $breakings = Breaking::where('work_id', $work_id)
                ->where(function ($q) use ($date) {
                    $q->whereDate('start_time', $date)
                        ->orWhereDate('end_time', $date);
                })->get();

            $breaking_total = new DateTime($date);

            foreach ($breakings as $breaking) {
                $breaking_start = $breaking->start_time;
                $breaking_end = $breaking->end_time;

                if (!$breaking_start || !$breaking_end) {
                    continue;
                }

                if ($breaking_start->format('Y-m-d') === $breaking_end->format('Y-m-d')) {
                    $breaking_time = $breaking_start->diff($breaking_end);
                } elseif ($breaking_start->format('Y-m-d') === $date) {
                    $breaking_time = $breaking_start->diff(new DateTime($breaking_start->format('Y-m-d') . ' 23:59:59'));
                } elseif ($breaking_end->format('Y-m-d') === $date) {
                    $breaking_time = (new DateTime($breaking_end->format('Y-m-d') . ' 00:00:00'))->diff($breaking_end);
                } else {
                    continue;
                }

                $breaking_total->add($breaking_time);
            }

            // 実働時間 = 勤務時間 - 休憩時間
            $net_worked_time = $worked_time->diff($breaking_total);

            $attendances[] = [
                'user' => $user,
                'work_start' => $work_start->format('H:i:s'),
                'work_end' => $work_end->format('H:i:s'),
                'worked_time' => $net_worked_time->format('%H:%i:%s'),
                'breaking_total' => $breaking_total->format('H:i:s'),
            ];
        }

        // ページネーション処理
        $attendances = collect($attendances);
        $page = Paginator::resolveCurrentPage('page');
        $attendances = new LengthAwarePaginator(
            $attendances->forPage($page, 5),
            $attendances->count(),
            5,
            $page,
            ['path' => $request->url()]
        );

        return view('attendance', compact('date', 'attendances'));
    }


    public function userWorkListIndex(Request $request)
    {

        $user = Auth::user();
        $id = Auth::id();

        $attendances = $this->workTimeArray($id);

        $attendances = collect($attendances);
        $page = Paginator::resolveCurrentPage('page');
        $attendances = new LengthAwarePaginator(
            $attendances->forPage($page, 5),
            count($attendances),
            5,
            $page,
            array('path' => $request->url()),
        );

        return view('user_work_list', compact('attendances',));
    }



    public function userListIndex(Request $request)
    {

        $id = $request->only('id');

        $attendances = $this->workTimeArray($id);

        $attendances = collect($attendances);
        $page = Paginator::resolveCurrentPage('page');
        $attendances = new LengthAwarePaginator(
            $attendances->forPage($page, 5),
            count($attendances),
            5,
            $page,
            array('path' => $request->url()),
        );

        return view('user_work_list', compact('attendances',));
    }



    private function workTimeArray($id)
    {
        $works = Work::where('user_id', $id)->get();
        $attendances = [];

        foreach ($works as $work) {
            $work_id = $work['id'];
            $work_start = $work['start_time'];
            $work_end = $work['end_time'];

            // どちらかが null の場合はスキップ
            if (!$work_start || !$work_end) {
                continue;
            }

            $date = $work_start->format('Y-m-d');

            if ($work_start->format('Y-m-d') === $work_end->format('Y-m-d')) {
                $diff_time = $work_start->diff($work_end);
                $worked_time = new DateTime($date);
                $worked_time->add($diff_time);

                $breakings = Breaking::where('work_id', $work_id)->get();
                $breaking_total = new DateTime($date);

                foreach ($breakings as $breaking) {
                    if ($breaking['start_time'] && $breaking['end_time']) {
                        $breaking_time = $breaking['start_time']->diff($breaking['end_time']);
                        $breaking_total->add($breaking_time);
                    }
                }

                $net_worked_time = $worked_time->diff($breaking_total);

                $attendances[] = [
                    'date' => $date,
                    'work_start' => $work_start->format('H:i:s'),
                    'work_end' => $work_end->format('H:i:s'),
                    'worked_time' => $net_worked_time->format('%H:%i:%s'),
                    'breaking_total' => $breaking_total->format('H:i:s'),
                ];
            } else {
                // -------------------- 当日分 --------------------
                $date_end = new DateTime($work_start->format('Y-m-d') . '23:59:59');
                $diff_time = $work_start->diff($date_end);
                $worked_time_1 = new DateTime($date);
                $worked_time_1->add($diff_time);

                $breakings = Breaking::where('work_id', $work_id)->whereDate('start_time', $date)->get();
                $breaking_total = new DateTime($date);

                foreach ($breakings as $breaking) {
                    if ($breaking['start_time'] && $breaking['end_time']) {
                        if ($breaking['start_time']->format('Y-m-d') === $breaking['end_time']->format('Y-m-d')) {
                            $breaking_time = $breaking['start_time']->diff($breaking['end_time']);
                        } else {
                            $breaking_end = new DateTime($breaking['start_time']->format('Y-m-d') . '23:59:59');
                            $breaking_time = $breaking['start_time']->diff($breaking_end);
                        }
                        $breaking_total->add($breaking_time);
                    }
                }

                $net_worked_time_1 = $worked_time_1->diff($breaking_total);

                $attendances[] = [
                    'date' => $date,
                    'work_start' => $work_start->format('H:i:s'),
                    'work_end' => $date_end->format('H:i:s'),
                    'worked_time' => $net_worked_time_1->format('%H:%i:%s'),
                    'breaking_total' => $breaking_total->format('H:i:s'),
                ];

                // -------------------- 翌日分 --------------------
                $date = $work_end->format('Y-m-d');
                $date_start = new DateTime($date . '00:00:00');
                $diff_time = $date_start->diff($work_end);
                $worked_time_2 = new DateTime($date);
                $worked_time_2->add($diff_time);

                $breakings = Breaking::where('work_id', $work_id)->whereDate('end_time', $date)->get();
                $breaking_total = new DateTime($date);

                foreach ($breakings as $breaking) {
                    if ($breaking['start_time'] && $breaking['end_time']) {
                        if ($breaking['start_time']->format('Y-m-d') === $breaking['end_time']->format('Y-m-d')) {
                            $breaking_time = $breaking['start_time']->diff($breaking['end_time']);
                        } else {
                            $breaking_start = new DateTime($breaking['start_time']->format('Y-m-d') . '00:00:00');
                            $breaking_time = $breaking_start->diff($breaking['end_time']);
                        }
                        $breaking_total->add($breaking_time);
                    }
                }

                $net_worked_time_2 = $worked_time_2->diff($breaking_total);

                $attendances[] = [
                    'date' => $date,
                    'work_start' => $date_start->format('H:i:s'),
                    'work_end' => $work_end->format('H:i:s'),
                    'worked_time' => $net_worked_time_2->format('%H:%i:%s'),
                    'breaking_total' => $breaking_total->format('H:i:s'),
                ];
            }
        }

        return $attendances;
    }
}
