<?php

namespace Database\Seeders;

use App\Models\Criterion;
use Illuminate\Database\Seeder;

class CriterionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $criteria = [
            [
                'code' => 'C1',
                'name' => 'Nilai SMK (Sistem Manajemen Kinerja)',
                'type' => 'benefit',
                'scale_min' => 0,
                'scale_max' => 100,
                'default_weight' => 0.25,
                'description' => 'Evaluasi capaian kinerja berkala personel polri berbasis sistem manajemen kinerja.',
                'is_active' => true,
            ],
            [
                'code' => 'C2',
                'name' => 'Nilai Psikologi / Assessment Center',
                'type' => 'benefit',
                'scale_min' => 0,
                'scale_max' => 100,
                'default_weight' => 0.20,
                'description' => 'Hasil uji kompetensi psikologis, kepemimpinan, dan kematangan emosi.',
                'is_active' => true,
            ],
            [
                'code' => 'C3',
                'name' => 'Nilai Kesamaptaan Jasmani (TKJ)',
                'type' => 'benefit',
                'scale_min' => 0,
                'scale_max' => 100,
                'default_weight' => 0.15,
                'description' => 'Hasil tes kesamaptaan jasmani berkala (lari, pull-up, sit-up, push-up, shuttle run).',
                'is_active' => true,
            ],
            [
                'code' => 'C4',
                'name' => 'Masa Dinas dalam Pangkat (MDDP)',
                'type' => 'benefit',
                'scale_min' => 0,
                'scale_max' => 100,
                'default_weight' => 0.15,
                'description' => 'Lama pengabdian dalam pangkat terakhir untuk kematangan pengalaman tugas.',
                'is_active' => true,
            ],
            [
                'code' => 'C5',
                'name' => 'Penghargaan / Prestasi',
                'type' => 'benefit',
                'scale_min' => 0,
                'scale_max' => 100,
                'default_weight' => 0.15,
                'description' => 'Tanda kehormatan, piagam penghargaan Kapolda/Kapolri atas dedikasi dan pengungkapan kasus.',
                'is_active' => true,
            ],
            [
                'code' => 'C6',
                'name' => 'Catatan Pelanggaran Disiplin/Kode Etik',
                'type' => 'cost',
                'scale_min' => 0,
                'scale_max' => 100,
                'default_weight' => 0.10,
                'description' => 'Riwayat penindakan disiplin, KEPP (Kode Etik Profesi Polri), atau hukuman administratif (Cost).',
                'is_active' => true,
            ],
        ];

        foreach ($criteria as $item) {
            Criterion::query()->updateOrCreate(
                ['code' => $item['code']],
                $item
            );
        }
    }
}
