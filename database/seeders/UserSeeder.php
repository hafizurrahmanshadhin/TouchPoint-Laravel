<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder {
    public function run(): void {
        DB::table('users')->insert([
            [
                'id'                   => 1,
                'name'                 => 'admin',
                'email'                => 'admin@admin.com',
                'email_verified_at'    => Carbon::now(),
                'password'             => Hash::make('12345678'),
                'role'                 => 'admin',
                'status'               => 'active',
                'created_at'           => now(),
                'updated_at'           => now(),
            ],
            [
                'id'                   => 2,
                'name'                 => 'user',
                'email'                => 'user@user.com',
                'email_verified_at'    => Carbon::now(),
                'password'             => Hash::make('12345678'),
                'role'                 => 'user',
                'status'               => 'active',
                'created_at'           => now(),
                'updated_at'           => now(),
            ],
            [
                'id'                   => 3,
                'name'                 => 'Mehedi Hasan',
                'email'                => 'mehediasan45@gmail.com',
                'email_verified_at'    => Carbon::now(),
                'password'             => Hash::make('12345678'),
                'role'                 => 'admin',
                'status'               => 'active',
                'created_at'           => now(),
                'updated_at'           => now(),
            ],
        ]);
    }
}
