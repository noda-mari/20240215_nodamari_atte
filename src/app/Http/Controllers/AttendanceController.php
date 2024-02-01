<?php

namespace App\Http\Controllers;

use App\Models\Breaking;
use DateTime;
use Illuminate\Support\Facades\Auth;
use App\Models\Work;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $user_id = Auth::id();

        $date = Carbon::now();

        $work = Work::where('user_id', $user_id)->whereDate('start_time', $date)->first();

        if (empty($work)) {
            $work_start = true;
            [$work_end, $breaking_start, $breaking_end] = false;
            return view('index', compact('user', 'work_start', 'work_end', 'breaking_start', 'breaking_end'));
        } else {
            if ($work['start_time'] && ($work['end_time'] === null)) {
                $work_id = $work['id'];
                $breakings = Breaking::where('work_id', $work_id)->first();
                if (empty($breakings)) {
                    $work_start = false;
                    $work_end = true;
                    $breaking_start = true;
                    $breaking_end = false;
                    return view('index', compact('user', 'work_start', 'work_end', 'breaking_start', 'breaking_end'));
                }else{
                    $breakings = Breaking::where('work_id', $work_id)->get();
                    $breaking = $breakings[count($breakings) - 1];
                        if ($breaking['start_time'] && ($breaking['end_time'] === null)){
                            $work_start = false;
                            $work_end = true;
                            $breaking_start = false;
                            $breaking_end = true;
                            return view('index', compact('user', 'work_start', 'work_end', 'breaking_start', 'breaking_end'));
                        }elseif($breaking['start_time'] && ($breaking['end_time'])){
                            $work_start = false;
                            $work_end = true;
                            $breaking_start = true;
                            $breaking_end = false;
                            return view('index', compact('user', 'work_start', 'work_end', 'breaking_start', 'breaking_end'));
                    }
                }
            } else {
                $work_start = false;
                [$work_end, $breaking_start, $breaking_end] = false;
                return view('index', compact('user', 'work_start', 'work_end', 'breaking_start', 'breaking_end'));
            }
        }
    }

    public function startTimeAdd()
    {
        $id = Auth::id();

        $start_date = Carbon::now();

        Work::create([
            'user_id' => $id,
            'date' => $start_date,
            'start_time' => $start_date,
        ]);

        return Redirect('/');
    }

    public function endTimeAdd()
    {
        $id = Auth::id();

        $end_date = Carbon::now();

        $works = Work::where('user_id', $id)->get();

        foreach ($works as $work)

            if ($work['end_time'] === null) {
                $work->update(['end_time' => $end_date]);

                return redirect('/');
            }

        return redirect('/');
    }
}
