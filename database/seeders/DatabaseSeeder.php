<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create default admin user
        User::firstOrCreate(
            ['email' => 'admin@ashram.in'],
            [
                'name'     => 'Admin — श्री आनंदम धाम',
                'email'    => 'admin@ashram.in',
                'password' => Hash::make('Admin@1234'),
            ]
        );

        // Create staff user
        User::firstOrCreate(
            ['email' => 'staff@ashram.in'],
            [
                'name'     => 'Staff User',
                'email'    => 'staff@ashram.in',
                'password' => Hash::make('Staff@1234'),
            ]
        );
    }
}
