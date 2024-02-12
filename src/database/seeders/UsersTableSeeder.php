<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => '山田花子',
            'email' => 'hanako@test.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$0gB305P/aeyHfxxYi5q6aOc.6wHFYfiNJNlNHTXrxEcwlLVE6zhey',
            'created_at' => now(),
            'updated_at' => now()
        ];
        DB::table('users')->insert($param);
    }
}
