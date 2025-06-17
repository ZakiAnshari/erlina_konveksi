<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Roles;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Admin'],
            ['name' => 'Bendahara']
        ];
        foreach ($roles as $role) {
            Roles::create($role);
        }
        // Create User
        User::create([
            'name' => 'Admin Erlina',
            'username' => 'Admin',
            'contact' => '082202020202',
            'role_id' => 1,
            'email' => 'admin@example.com',
            'jenis_kelamin' => 'Laki-Laki',
            'email_verified_at' => now(),
            'password' => Hash::make('123'), // Ganti dengan password yang aman
            'remember_token' => Str::random(10),
        ]);
        // Bendahara
        User::create([
            'name' => 'Bendahara Erlina',
            'username' => 'Bendahara',
            'contact' => '085501010101',
            'role_id' => 2, // Misalnya 2 = Bendahara
            'email' => 'bendahara@example.com',
            'jenis_kelamin' => 'Perempuan',
            'email_verified_at' => now(),
            'password' => Hash::make('123'), // Ganti juga di production
            'remember_token' => Str::random(10),
        ]);
    }
}
