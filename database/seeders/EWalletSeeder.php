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
                'logo' => 'https://www.xendit.co/wp-content/uploads/2019/11/logo-ovo.png',
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
                'logo' => 'https://www.xendit.co/wp-content/uploads/2019/11/logo-dana.png',
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
                'logo' => 'https://www.xendit.co/wp-content/uploads/2019/11/logo-linkaja.png',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
