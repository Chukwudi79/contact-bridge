<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach ([
            ['name' => 'Primary Administrator', 'email' => 'admin@example.com', 'password' => 'Admin@12345'],
            ['name' => 'Operations Manager', 'email' => 'operations@example.com', 'password' => 'Operations@12345'],
            ['name' => 'Support Agent', 'email' => 'support@example.com', 'password' => 'Support@12345'],
        ] as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [...$user, 'is_admin' => true, 'email_verified_at' => now()],
            );
        }
    }
}
