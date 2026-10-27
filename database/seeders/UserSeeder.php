<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Administrator SDM',
                'email' => 'superadmin@polda.polri.go.id',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'phone' => '081100000001',
                'is_active' => true,
            ],
            [
                'name' => 'Operator Bagbinkar SDM',
                'email' => 'adminsdm@polda.polri.go.id',
                'password' => Hash::make('password'),
                'role' => 'admin_sdm',
                'phone' => '081100000002',
                'is_active' => true,
            ],
            [
                'name' => 'Kapolda / Tim Wanjak',
                'email' => 'kapolda@polda.polri.go.id',
                'password' => Hash::make('password'),
                'role' => 'pimpinan',
                'phone' => '081100000003',
                'is_active' => true,
            ],
        ];

        foreach ($users as $userData) {
            User::query()->updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
