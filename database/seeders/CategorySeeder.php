<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('categories')->insert([
            [
            'type_id' => 1, // 'SKD (Seleksi Kompetensi Dasar)'
            'name' => 'TWK',
            'full_name' => 'Tes Wawasan Kebangsaan',
            'slug' => 'tes-wawasan-kebangsaan',
            'description' => 'Kategori ini berisi materi-materi yang berkaitan dengan Tes Wawasan Kebangsaan (TWK) CPNS',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            ],
            [
            'type_id' => 1, // 'SKD (Seleksi Kompetensi Dasar)'
            'name' => 'TIU',
            'full_name' => 'Tes Intelegensi Umum',
            'slug' => 'tes-intelegensi-umum',
            'description' => 'Kategori ini berisi materi-materi yang berkaitan dengan Tes Intelegensi Umum (TIU) CPNS',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            ],
            [
            'type_id' => 1, // 'SKD (Seleksi Kompetensi Dasar)'
            'name' => 'TKP',
            'full_name' => 'Tes Karakteristik Pribadi',
            'slug' => 'tes-karakteristik-pribadi',
            'description' => 'Kategori ini berisi materi-materi yang berkaitan dengan Tes Karakteristik Pribadi (TKP) CPNS',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
