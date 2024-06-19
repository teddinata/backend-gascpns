<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EWalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('ewallets')->insert([
            [
                'name' => 'OVO',
                'code' => 'OVO',
                'ewallet_type' => 'OVO',
                'country' => 'ID',
                'currency' => 'IDR',
                'is_activated' => true,
                'logo' => 'https://dashboard.xendit.co/assets/images/ovo-logo.svg',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'DANA',
                'code' => 'DANA',
                'ewallet_type' => 'DANA',
                'country' => 'ID',
                'currency' => 'IDR',
                'is_activated' => true,
                'logo' => 'https://dashboard.xendit.co/assets/images/dana-logo.svg',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'LinkAja',
                'code' => 'LINKAJA',
                'ewallet_type' => 'LINKAJA',
                'country' => 'ID',
                'currency' => 'IDR',
                'is_activated' => false,
                'logo' => 'https://dashboard.xendit.co/assets/images/linkaja-logo.svg',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'ShopeePay',
                'code' => 'SHOPEEPAY',
                'ewallet_type' => 'SHOPEEPAY',
                'country' => 'ID',
                'currency' => 'IDR',
                'is_activated' => false,
                'logo' => 'https://dashboard.xendit.co/assets/images/shopeepay-logo.svg',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'AstraPay',
                'code' => 'ASTRAPAY',
                'ewallet_type' => 'ASTRAPAY',
                'country' => 'ID',
                'currency' => 'IDR',
                'is_activated' => false,
                'logo' => 'https://dashboard.xendit.co/assets/images/astrapay-logo.svg',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
