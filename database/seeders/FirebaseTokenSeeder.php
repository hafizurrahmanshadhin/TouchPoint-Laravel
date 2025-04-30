<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class FirebaseTokenSeeder extends Seeder {
    public function run(): void {
        DB::table('firebase_tokens')->insert([
            [
                'id'         => 1,
                'user_id'    => 3,
                'token'      => 'eNSsuIDuT_2oeXnNPbUqhX:APA91bE0g2l4lt_5xFSC3C_9HOgyor0u-5ukvIQOeZh_EYscHFAWDMdz5PGBMMwcbRe8A6volwfvMvnXFNwrg2XpsNy4ZRMvcvUcEU69iAoPlLDvrZcR4aU',
                'device_id'  => null,
                'status'     => 'active',
                'created_at' => Carbon::parse('2025-04-30 05:22:33'),
                'updated_at' => Carbon::parse('2025-04-30 05:22:33'),
                'deleted_at' => null,
            ],
        ]);
    }
}
