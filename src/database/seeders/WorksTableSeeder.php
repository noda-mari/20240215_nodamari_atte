<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Datetime;

class WorksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'user_id' => 1,
            'start_time' => new DateTime('2024-02-15 22:00:00'),
            'end_time' => new Datetime('2024-02-16 09:00:00'),
        ];
        DB::table('works')->insert($param);

        $param = [
            'user_id' => 1,
            'start_time' => new DateTime('2024-02-17 15:00:00'),
            'end_time' => new Datetime('2024-02-17 23:30:00'),
        ];
        DB::table('works')->insert($param);

        $param = [
            'user_id' => 1,
            'start_time' => new DateTime('2024-02-18 12:00:00'),
            'end_time' => new Datetime('2024-02-18 21:00:00'),
        ];
        DB::table('works')->insert($param);
    }
}
