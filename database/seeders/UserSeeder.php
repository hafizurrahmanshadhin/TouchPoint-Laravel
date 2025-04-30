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
                'id'                => 1,
                'first_name'        => 'admin',
                'last_name'         => 'admin',
                'email'             => 'admin@admin.com',
                'email_verified_at' => Carbon::parse('2025-04-30 05:22:10'),
                'password'          => Hash::make('12345678'),
                'avatar'            => null,
                'cover_photo'       => null,
                'google_id'         => null,
                'apple_id'          => null,
                'badge'             => 'bronze',
                'role'              => 'admin',
                'status'            => 'active',
                'remember_token'    => null,
                'created_at'        => Carbon::parse('2025-04-30 05:22:10'),
                'updated_at'        => Carbon::parse('2025-04-30 05:22:10'),
                'deleted_at'        => null,
            ],
            [
                'id'                => 2,
                'first_name'        => 'user',
                'last_name'         => 'user',
                'email'             => 'user@user.com',
                'email_verified_at' => Carbon::parse('2025-04-30 05:22:10'),
                'password'          => Hash::make('12345678'),
                'avatar'            => null,
                'cover_photo'       => null,
                'google_id'         => null,
                'apple_id'          => null,
                'badge'             => 'bronze',
                'role'              => 'user',
                'status'            => 'active',
                'remember_token'    => null,
                'created_at'        => Carbon::parse('2025-04-30 05:22:10'),
                'updated_at'        => Carbon::parse('2025-04-30 05:22:10'),
                'deleted_at'        => null,
            ],
            [
                'id'                => 3,
                'first_name'        => 'Hafizur',
                'last_name'         => 'Rahman',
                'email'             => 'shadhin666@gmail.com',
                'email_verified_at' => Carbon::parse('2025-04-27 00:34:18'),
                'password'          => '$2y$12$izgEdTOB5N6Z94JnWkKYxOxLX4qB3QyLjcX9bWouTpjBWQLErV5nW',
                'avatar'            => 'https://lh3.googleusercontent.com/a/ACg8ocJeAu4Hk_Usr3f-cm2plZBHggk5O-jxWNzexfWXqiUpF6xov2GU=s96-c',
                'cover_photo'       => null,
                'google_id'         => '116839953501233813796',
                'apple_id'          => null,
                'badge'             => 'silver',
                'role'              => 'user',
                'status'            => 'active',
                'remember_token'    => null,
                'created_at'        => Carbon::parse('2025-04-27 00:34:18'),
                'updated_at'        => Carbon::parse('2025-04-30 05:24:36'),
                'deleted_at'        => null,
            ],
        ]);
    }
}
