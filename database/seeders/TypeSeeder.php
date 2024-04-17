<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('types')->insert([
            [
            'name' => 'SKD (Seleksi Kompetensi Dasar)',
            'slug' => 'seleksi-kompetensi-dasar',
            'description' => 'Kategori ini berisi materi-materi yang berkaitan dengan Seleksi Kompetensi Dasar (SKD) CPNS',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            ],
            [
            'name' => 'SKB (Seleksi Kompetensi Bidang)',
            'slug' => 'seleksi-kompetensi-bidang',
            'description' => 'Kategori ini berisi materi-materi yang berkaitan dengan Seleksi Kompetensi Bidang (SKB) CPNS',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
