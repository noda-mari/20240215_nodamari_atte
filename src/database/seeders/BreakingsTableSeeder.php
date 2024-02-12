<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Datetime;

class BreakingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'work_id' => 1,
            'start_time' => new DateTime('2024-02-12 23:30:00'),
            'end_time' => new Datetime('2024-02-13 00:00:00'),
        ];
        DB::table('breakings')->insert($param);

        $param = [
            'work_id' => 1,
            'start_time' => new DateTime('2024-02-13 01:30:00'),
            'end_time' => new Datetime('2024-02-13 02:00:00'),
        ];
        DB::table('breakings')->insert($param);

        $param = [
            'work_id' => 1,
            'start_time' => new DateTime('2024-02-13 07:30:00'),
            'end_time' => new Datetime('2024-02-13 08:00:00'),
        ];
        DB::table('breakings')->insert($param);

    }
}
