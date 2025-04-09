<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::firstOrCreate(
            ['email' => 'hanako@test.com'], // ← 一意キー
            [
                'name' => '山田花子',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'), // パスワードはHash化
            ]
        );
    }
}
