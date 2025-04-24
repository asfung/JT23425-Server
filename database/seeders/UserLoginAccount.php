<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserLoginAccount extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create(
            [
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin'),
            ],
            [
            'name' => 'paung',
            'email' => 'paung@gmail.com',
            'password' => bcrypt('paung'),
            ],
            [
            'name' => 'kalengsarden',
            'email' => 'kalengsarden@gmail.com',
            'password' => bcrypt('kalengsarden'),
            ],
        );
    }
}
