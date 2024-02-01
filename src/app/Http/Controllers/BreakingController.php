<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\Auth;
use App\Models\Breaking;
use App\Models\Work;
use Carbon\Carbon;

class BreakingController extends Controller
{
    public function startTimeAdd(){

        $id = Auth::id();

        $start_time = Carbon::now();

        $works = Work::where('user_id',$id)->get();
        $work = $works[count($works)-1];

        $work_id = $work['id'];

        Breaking::create([
            'work_id' => $work_id,
            'start_time' => $start_time,
        ]);

        return Redirect('/');
    }

    public function endTimeAdd(){

        $id = Auth::id();

        $end_time = Carbon::now();

        $work = Work::where('user_id', $id)->whereNull('end_time')->first();

        $work_id = $work['id'];

        $breakings = Breaking::where('work_id',$work_id)->get();

        foreach($breakings as $breaking)

        if($breaking['end_time'] === null){

            $breaking->update([
            'end_time' => $end_time, ]);

            return redirect('/');
        }

        return redirect('/');
    }
}