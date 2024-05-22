<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('banks')->insert([
            [
                'name' => 'Bank Central Asia',
                'code' => 'BCA',
                'bank_code' => 'BCA',
                'country' => 'ID',
                'currency' => 'IDR',
                'is_activated' => false,
                'logo' => 'https://www.xendit.co/wp-content/uploads/2019/11/logo-bca.png',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Bank Negara Indonesia',
                'code' => 'BNI',
                'bank_code' => 'BNI',
                'country' => 'ID',
                'currency' => 'IDR',
                'is_activated' => true,
                'logo' => 'https://www.xendit.co/wp-content/uploads/2019/11/logo-bni.png',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Bank Rakyat Indonesia',
                'code' => 'BRI',
                'bank_code' => 'BRI',
                'country' => 'ID',
                'currency' => 'IDR',
                'is_activated' => true,
                'logo' => 'https://www.xendit.co/wp-content/uploads/2019/11/logo-bri.png',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Bank Mandiri',
                'code' => 'MANDIRI',
                'bank_code' => 'MANDIRI',
                'country' => 'Indonesia',
                'currency' => 'IDR',
                'is_activated' => true,
                'logo' => 'https://www.xendit.co/wp-content/uploads/2019/11/logo-mandiri.png',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Bank Syariah Indonesia',
                'code' => 'BSI',
                'bank_code' => 'BSI',
                'country' => 'ID',
                'currency' => 'IDR',
                'is_activated' => true,
                'logo' => 'https://www.xendit.co/wp-content/uploads/2021/11/BSI-Logo-2.png',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Bank CIMB Niaga',
                'code' => 'CIMB',
                'bank_code' => 'CIMB',
                'country' => 'ID',
                'currency' => 'IDR',
                'is_activated' => true,
                'logo' => 'https://www.xendit.co/wp-content/uploads/2021/06/CIMB-Niaga.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Bank Permata',
                'code' => 'PERMATA',
                'bank_code' => 'PERMATA',
                'country' => 'ID',
                'currency' => 'IDR',
                'is_activated' => true,
                'logo' => 'https://www.xendit.co/wp-content/uploads/2019/11/logo-permatabank.png',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
