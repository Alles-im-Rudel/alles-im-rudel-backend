<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $users = $this->getUsers();

        foreach ($users as $user) {
            User::firstOrCreate([
                'email' => $user['email']
            ], [
                'first_name'        => $user['first_name'],
                'last_name'         => $user['last_name'],
                'password'          => Hash::make($user['password']),
                'username'          => $user['username'],
                'email_verified_at' => $user['email_verified_at'],
                'activated_at'      => $user['activated_at'],
            ]);
        }
    }

    public function getUsers(): array
    {
        $now = Carbon::now();
        return [
            [
                'first_name'        => 'Dev',
                'last_name'         => 'Dev',
                'username'          => 'dev',
                'password'          => '123456',
                'email'             => 'dev@allesimrudel.de',
                'email_verified_at' => $now,
                'activated_at'      => $now,
            ],
        ];
    }

}
