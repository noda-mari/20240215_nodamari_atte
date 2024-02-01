<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Breaking extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_id',
        'start_time',
        'end_time',
    ];

    protected $dates   = ['start_time', 'end_time'];

    // public function getTime(){
        // return $this->start_time->duff($this->end_time);
    // }

    public function work()
    {
        return $this->belongsTo(Work::class);
    }
}
