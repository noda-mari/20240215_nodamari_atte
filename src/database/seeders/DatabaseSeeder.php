<?php

namespace Database\Seeders;

use App\Models\Work;
use App\Models\User;
use App\Models\Breaking;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(WorksTableSeeder::class);
        $this->call(BreakingsTableSeeder::class);
        User::factory(9)->create();
        Work::factory(30)->create();
    }
}
