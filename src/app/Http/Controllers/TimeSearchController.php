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
        if (isset($request['date_add'])) {
            $date = $request['date_add'];
            $date = new DateTime($date);
            $date->modify('1 day');
            $date = $date->format('Y-m-d');
        } elseif (isset($request['date_sub'])) {
            $date = $request['date_sub'];
            $date = new DateTime($date);
            $date->modify('-1 days');
            $date = $date->format('Y-m-d');
        } else {
            $date = $request['date'];
        }
        $works = Work::with('user')->whereDate('start_time', $date)->orWhereDate('end_time', $date)->get();
        $attendances = array();
        foreach ($works as $work) {
            $user = $work['user']['name'];
            $work_id = $work['id'];
            $work_start = $work['start_time'];
            $work_end = $work['end_time'];

            if ($work_start->format('Y-m-d') === $work_end->format('Y-m-d')) {
                $diff_time = $work_start->diff($work_end);
                $worked_time = new DateTime($date);
                $worked_time->add($diff_time);
            } else {
                if ($work_start->format('Y-m-d') === $date) {
                    $work_end = new DateTime($work_start->format('Y-m-d') . '23:59:59');
                    $diff_time = $work_start->diff($work_end);
                    $worked_time = new DateTime($date);
                    $worked_time->add($diff_time);
                } elseif ($work_end->format('Y-m-d') === $date) {
                    $work_start = new DateTime($work_end->format('Y-m-d') . '00:00:00');
                    $diff_time = $work_start->diff($work_end);
                    $worked_time = new DateTime($date);
                    $worked_time->add($diff_time);
                }
            }
            $breakings = Breaking::where('work_id', $work_id)
                ->where(function ($query) use ($date) {
                    $query->whereDate('start_time', $date)
                        ->orWhereDate('end_time', $date);
                })
                ->get();

            $breaking_total = new DateTime($date);
            foreach ($breakings as $breaking) {
                $breaking_start = $breaking['start_time'];
                $breaking_end = $breaking['end_time'];
                if ($breaking_start->format('Y-m-d') === $breaking_end->format('Y-m-d')) {
                    $breaking_time = $breaking_start->diff($breaking_end);
                } else {
                    if ($breaking_start->format('Y-m-d') === $date) {
                        $breaking_end = new DateTime($breaking_start->format('Y-m-d') . '23:59:59');
                        $breaking_time = $breaking_start->diff($breaking_end);
                    } elseif ($breaking_end->format('Y-m-d') === $date) {
                        $breaking_start = new DateTime($breaking_end->format('Y-m-d') . '00:00:00');
                        $breaking_time = $breaking_start->diff($breaking_end);
                    }
                }
                $breaking_total->add($breaking_time);
            }
            if ($breaking_total) {
                $worked_time = $worked_time->diff($breaking_total);
            }
            $attendances[] = array(
                'user' => $user,
                'work_start' => $work_start->format('H:i:s'),
                'work_end' => $work_end->format('H:i:s'),
                'worked_time' => $worked_time->format('%H:%i:%s'),
                'breaking_total' => $breaking_total->format('H:i:s')
            );
        }
        $attendances = collect($attendances);
        $page = Paginator::resolveCurrentPage('page');
        $attendances = new LengthAwarePaginator(
            $attendances->forPage($page, 5),
            count($attendances),
            5,
            $page,
            array('path' => $request->url()),
        );
        return view('attendance', compact('date', 'attendances',));
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



    public function workTimeArray($id)
    {

        $works = Work::where('user_id', $id)->get();

        $attendances = array();

        foreach ($works as $work) {
            $work_id = $work['id'];
            $work_start = $work['start_time'];
            $work_end = $work['end_time'];
            $date = $work_start->format('Y-m-d');

            if ($work_start->format('Y-m-d') === $work_end->format('Y-m-d')) {
                $diff_time = $work_start->diff($work_end);
                $worked_time = new DateTime($date);
                $worked_time->add($diff_time);

                $breakings = Breaking::where('work_id', $work_id)->get();
                $breaking_total = new DateTime($date);

                foreach ($breakings as $breaking) {
                    $breaking_time = $breaking['start_time']->diff($breaking['end_time']);
                    $breaking_total->add($breaking_time);
                }

                if ($breaking_total) {
                    $worked_time = $worked_time->diff($breaking_total);
                }

                $attendances[] = array(
                    'date' => $date,
                    'work_start' => $work_start->format('H:i:s'),
                    'work_end' => $work_end->format('H:i:s'),
                    'worked_time' => $worked_time->format('%H:%i:%s'),
                    'breaking_total' => $breaking_total->format('H:i:s')
                );
            } else {
                $date_end = new DateTime($work_start->format('Y-m-d') . '23:59:59');
                $diff_time = $work_start->diff($date_end);
                $worked_time_1 = new DateTime($date);
                $worked_time_1->add($diff_time);

                $breakings = Breaking::where('work_id', $work_id)->whereDate('start_time', $date)->get();
                $breaking_total = new DateTime($date);

                foreach ($breakings as $breaking) {
                    if ($breaking['start_time']->format('Y-m-d') === $breaking['end_time']->format('Y-m-d')) {
                        $breaking_time = $breaking['start_time']->diff($breaking['end_time']);
                    } else {
                        $breaking_end = new DateTime($breaking['start_time']->format('Y-m-d') . '23:59:59');
                        $breaking_time = $breaking['start_time']->diff($breaking_end);
                    }
                    $breaking_total->add($breaking_time);
                }
                if ($breaking_total) {
                    $worked_time = $worked_time_1->diff($breaking_total);
                }

                $attendances[] = array(
                    'date' => $date,
                    'work_start' => $work_start->format('H:i:s'),
                    'work_end' => $date_end->format('H:i:s'),
                    'worked_time' => $worked_time->format('%H:%i:%s'),
                    'breaking_total' => $breaking_total->format('H:i:s')
                );

                $date_start = new DateTime($work_end->format('Y-m-d') . '00:00:00'); //次の日の初めから就業時間までの計算
                $diff_time = $date_start->diff($work_end);
                $date = $work_end->format('Y-m-d');
                $worked_time_2 = new DateTime($date);
                $worked_time_2->add($diff_time);

                $breakings = Breaking::where('work_id', $work_id)->whereDate('end_time', $date)->get();
                $breaking_total = new DateTime($date);

                foreach ($breakings as $breaking) {
                    if ($breaking['start_time']->format('Y-m-d') === $breaking['end_time']->format('Y-m-d')) {
                        $breaking_time = $breaking['start_time']->diff($breaking['end_time']);
                    } else {
                        $breaking_start = new DateTime($breaking['start_time']->format('Y-m-d') . '00:00:00');
                        $breaking_time = $breaking_start->diff($breaking['end_time']);
                    }
                    $breaking_total->add($breaking_time);
                }
                if ($breaking_total) {
                    $worked_time_2 = $worked_time_1->diff($breaking_total);
                }

                $attendances[] = array(
                    'date' => $date,
                    'work_start' => $date_start->format('H:i:s'),
                    'work_end' => $work_end->format('H:i:s'),
                    'worked_time' => $worked_time_2->format('%H:%i:%s'),
                    'breaking_total' => $breaking_total->format('H:i:s')
                );
            }
        }

        return $attendances;
    }
}
