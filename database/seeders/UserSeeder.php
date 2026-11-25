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
        // Admin Account
        User::updateOrCreate(
            ['email' => 'admin@schoolcare.sch.id'],
            [
                'name' => 'Administrator Sarpras',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'phone' => '081234567890',
            ]
        );

        // Student Accounts
        User::updateOrCreate(
            ['email' => 'ahmad@siswa.schoolcare.sch.id'],
            [
                'name' => 'Ahmad Fauzi',
                'role' => 'siswa',
                'nisn' => '20241001',
                'kelas' => 'XII RPL 1',
                'password' => Hash::make('password'),
                'phone' => '089876543210',
            ]
        );

        User::updateOrCreate(
            ['email' => 'siti@siswa.schoolcare.sch.id'],
            [
                'name' => 'Siti Nurhaliza',
                'role' => 'siswa',
                'nisn' => '20241002',
                'kelas' => 'XI TKJ 2',
                'password' => Hash::make('password'),
                'phone' => '087712345678',
            ]
        );

        User::updateOrCreate(
            ['email' => 'budi@siswa.schoolcare.sch.id'],
            [
                'name' => 'Budi Pratama',
                'role' => 'siswa',
                'nisn' => '20241003',
                'kelas' => 'XII MM 1',
                'password' => Hash::make('password'),
                'phone' => '085612345678',
            ]
        );
    }
}

