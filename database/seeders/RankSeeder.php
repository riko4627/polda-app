<?php

namespace Database\Seeders;

use App\Models\Rank;
use Illuminate\Database\Seeder;

class RankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ranks = [
            // Tamtama (Level 1 - 6)
            ['level_order' => 1, 'code' => 'BHARADA', 'name' => 'Bhayangkara Dua', 'category' => 'tamtama'],
            ['level_order' => 2, 'code' => 'BHARATU', 'name' => 'Bhayangkara Satu', 'category' => 'tamtama'],
            ['level_order' => 3, 'code' => 'BHARAKA', 'name' => 'Bhayangkara Kepala', 'category' => 'tamtama'],
            ['level_order' => 4, 'code' => 'ABRIPDA', 'name' => 'Ajun Brigadir Polisi Dua', 'category' => 'tamtama'],
            ['level_order' => 5, 'code' => 'ABRIPTU', 'name' => 'Ajun Brigadir Polisi Satu', 'category' => 'tamtama'],
            ['level_order' => 6, 'code' => 'ABRIP', 'name' => 'Ajun Brigadir Polisi', 'category' => 'tamtama'],

            // Bintara (Level 7 - 12)
            ['level_order' => 7, 'code' => 'BRIPDA', 'name' => 'Brigadir Polisi Dua', 'category' => 'bintara'],
            ['level_order' => 8, 'code' => 'BRIPTU', 'name' => 'Brigadir Polisi Satu', 'category' => 'bintara'],
            ['level_order' => 9, 'code' => 'BRIGPOL', 'name' => 'Brigadir Polisi', 'category' => 'bintara'],
            ['level_order' => 10, 'code' => 'BRIPKA', 'name' => 'Brigadir Polisi Kepala', 'category' => 'bintara'],
            ['level_order' => 11, 'code' => 'AIPDA', 'name' => 'Ajun Inspektur Polisi Dua', 'category' => 'bintara'],
            ['level_order' => 12, 'code' => 'AIPTU', 'name' => 'Ajun Inspektur Polisi Satu', 'category' => 'bintara'],

            // Perwira Pertama / Pama (Level 13 - 15)
            ['level_order' => 13, 'code' => 'IPDA', 'name' => 'Inspektur Polisi Dua', 'category' => 'pama'],
            ['level_order' => 14, 'code' => 'IPTU', 'name' => 'Inspektur Polisi Satu', 'category' => 'pama'],
            ['level_order' => 15, 'code' => 'AKP', 'name' => 'Ajun Komisaris Polisi', 'category' => 'pama'],

            // Perwira Menengah / Pamen (Level 16 - 18)
            ['level_order' => 16, 'code' => 'KOMPOL', 'name' => 'Komisaris Polisi', 'category' => 'pamen'],
            ['level_order' => 17, 'code' => 'AKBP', 'name' => 'Ajun Komisaris Besar Polisi', 'category' => 'pamen'],
            ['level_order' => 18, 'code' => 'KOMBES', 'name' => 'Komisaris Besar Polisi', 'category' => 'pamen'],

            // Perwira Tinggi / Pati (Level 19 - 22)
            ['level_order' => 19, 'code' => 'BRIGJEN_POL', 'name' => 'Brigadir Jenderal Polisi', 'category' => 'pati'],
            ['level_order' => 20, 'code' => 'IRJEN_POL', 'name' => 'Inspektur Jenderal Polisi', 'category' => 'pati'],
            ['level_order' => 21, 'code' => 'KOMJEN_POL', 'name' => 'Komisaris Jenderal Polisi', 'category' => 'pati'],
            ['level_order' => 22, 'code' => 'JENDERAL_POL', 'name' => 'Jenderal Polisi', 'category' => 'pati'],
        ];

        foreach ($ranks as $rank) {
            Rank::query()->updateOrCreate(
                ['code' => $rank['code']],
                $rank
            );
        }
    }
}
