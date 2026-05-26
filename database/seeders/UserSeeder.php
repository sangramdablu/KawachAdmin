<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create role (if not exists)
        $role = Role::firstOrCreate([
            'name' => 'super-admin',
            'guard_name' => 'web',
        ]);

        // 2. Create or update user
        $user = User::updateOrCreate(
            ['email' => 'contact@kawachtech.com'],
            [
                'name' => 'Kawach Tech',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('Kawach@123'),
            ]
        );

        // 3. Assign role
        $user->assignRole($role);
    }
}