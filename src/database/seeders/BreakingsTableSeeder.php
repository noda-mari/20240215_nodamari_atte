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
            'start_time' => new DateTime('2024-02-15 23:30:00'),
            'end_time' => new Datetime('2024-02-16 00:00:00'),
        ];
        DB::table('breakings')->insert($param);

        $param = [
            'work_id' => 1,
            'start_time' => new DateTime('2024-02-16 01:30:00'),
            'end_time' => new Datetime('2024-02-16 02:00:00'),
        ];
        DB::table('breakings')->insert($param);

        $param = [
            'work_id' => 1,
            'start_time' => new DateTime('2024-02-16 07:30:00'),
            'end_time' => new Datetime('2024-02-16 08:00:00'),
        ];
        DB::table('breakings')->insert($param);

        $param = [
            'work_id' => 2,
            'start_time' => new DateTime('2024-02-17 16:00:00'),
            'end_time' => new Datetime('2024-02-17 16:30:00'),
        ];
        DB::table('breakings')->insert($param);

        $param = [
            'work_id' => 2,
            'start_time' => new DateTime('2024-02-17 19:00:00'),
            'end_time' => new Datetime('2024-02-17 19:30:00'),
        ];
        DB::table('breakings')->insert($param);

        $param = [
            'work_id' => 2,
            'start_time' => new DateTime('2024-02-17 21:00:00'),
            'end_time' => new Datetime('2024-02-17 21:10:00'),
        ];
        DB::table('breakings')->insert($param);

        $param = [
            'work_id' => 3,
            'start_time' => new DateTime('2024-02-18 13:00:00'),
            'end_time' => new Datetime('2024-02-18 13:30:00'),
        ];
        DB::table('breakings')->insert($param);

        $param = [
            'work_id' => 3,
            'start_time' => new DateTime('2024-02-18 18:00:00'),
            'end_time' => new Datetime('2024-02-18 18:30:00'),
        ];
        DB::table('breakings')->insert($param);

        $param = [
            'work_id' => 3,
            'start_time' => new DateTime('2024-02-18 20:00:00'),
            'end_time' => new Datetime('2024-02-18 20:10:00'),
        ];
        DB::table('breakings')->insert($param);
    }
}
