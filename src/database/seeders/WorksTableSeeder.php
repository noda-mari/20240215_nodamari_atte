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
            'start_time' => new DateTime('2024-02-12 22:00:00'),
            'end_time' => new Datetime('2024-02-13 09:00:00'),
        ];
        DB::table('works')->insert($param);
    }
}
