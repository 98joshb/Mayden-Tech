<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'User',
            'slug' => 'user',
            // 'permissions' => json_encode([
            //     'create-post' => true,
            // ]),
        ]);

        User::create([
            'name' => 'Steve',
            'email' => 'Steve@example.com',
            'password' => Hash::make('password'),
            'role_id' => 1,
        ]);
    }
}
