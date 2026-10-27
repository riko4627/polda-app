<?php

namespace Database\Seeders;

use App\Models\Criterion;
use App\Models\Position;
use App\Models\PositionCriterion;
use App\Models\PositionRule;
use App\Models\Rank;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $akbp = Rank::query()->where('code', 'AKBP')->first();
        $iptu = Rank::query()->where('code', 'IPTU')->first();

        $positionsData = [
            [
                'position' => [
                    'code' => 'KAPOLSEK',
                    'title' => 'Kepala Kepolisian Sektor (Kapolsek)',
                    'unit_name' => 'Polsek Jajaran',
                    'quota' => 3,
                    'description' => 'Pimpinan unsur pelaksana utama kewilayahan di tingkat kecamatan.',
                    'is_active' => true,
                ],
                'rule' => [
                    'min_rank_id' => $iptu?->id,
                    'required_rank_id' => null,
                    'required_rank_category' => null,
                    'min_education_level' => null,
                    'requires_sespimmen' => false,
                    'required_certification_keyword' => null,
                    'required_past_position_title' => null,
                    'required_function_track' => null,
                    'notes' => 'Minimal berpangkat Iptu (Iptu, AKP, Kompol).',
                ],
            ],
            [
                'position' => [
                    'code' => 'KABAGDALPERS',
                    'title' => 'Kepala Bagian Pengendalian Personel (Kabagdalpers)',
                    'unit_name' => 'Biro SDM Polda',
                    'quota' => 1,
                    'description' => 'Unsur pembantu pimpinan bidang pengendalian personel di Biro SDM Polda.',
                    'is_active' => true,
                ],
                'rule' => [
                    'min_rank_id' => null,
                    'required_rank_id' => $akbp?->id,
                    'required_rank_category' => null,
                    'min_education_level' => null,
                    'requires_sespimmen' => true,
                    'required_certification_keyword' => null,
                    'required_past_position_title' => 'Kapolres',
                    'required_function_track' => null,
                    'notes' => 'Wajib AKBP, lulus Sespimmen, dan wajib pernah menjabat Kapolres.',
                ],
            ],
            [
                'position' => [
                    'code' => 'KAPOLRES',
                    'title' => 'Kepala Kepolisian Resor (Kapolres)',
                    'unit_name' => 'Polres Jajaran',
                    'quota' => 2,
                    'description' => 'Pimpinan kesatuan kewilayahan tingkat kabupaten/kota.',
                    'is_active' => true,
                ],
                'rule' => [
                    'min_rank_id' => null,
                    'required_rank_id' => $akbp?->id,
                    'required_rank_category' => null,
                    'min_education_level' => null,
                    'requires_sespimmen' => true,
                    'required_certification_keyword' => null,
                    'required_past_position_title' => null,
                    'required_function_track' => null,
                    'notes' => 'Wajib AKBP dan lulus Sespimmen.',
                ],
            ],
            [
                'position' => [
                    'code' => 'WADIR_RESKRIM',
                    'title' => 'Wakil Direktur Reserse Kriminal Umum (Wadir Reskrim)',
                    'unit_name' => 'Ditreskrimum Polda',
                    'quota' => 1,
                    'description' => 'Wakil pimpinan pelaksana fungsi penyelidikan dan penyidikan tindak pidana umum.',
                    'is_active' => true,
                ],
                'rule' => [
                    'min_rank_id' => null,
                    'required_rank_id' => $akbp?->id,
                    'required_rank_category' => null,
                    'min_education_level' => null,
                    'requires_sespimmen' => true,
                    'required_certification_keyword' => null,
                    'required_past_position_title' => null,
                    'required_function_track' => 'Reskrim',
                    'notes' => 'Wajib AKBP, Sespimmen, dan memiliki rekam jejak tugas di fungsi Reskrim.',
                ],
            ],
            [
                'position' => [
                    'code' => 'BINTARA_PENYIDIK',
                    'title' => 'Bintara Penyidik',
                    'unit_name' => 'Satreskrim / Ditreskrimum',
                    'quota' => 5,
                    'description' => 'Penyidik pembantu dalam penegakan hukum dan penyidikan tindak pidana.',
                    'is_active' => true,
                ],
                'rule' => [
                    'min_rank_id' => null,
                    'required_rank_id' => null,
                    'required_rank_category' => 'bintara',
                    'min_education_level' => null,
                    'requires_sespimmen' => false,
                    'required_certification_keyword' => 'Penyidik',
                    'required_past_position_title' => null,
                    'required_function_track' => null,
                    'notes' => 'Golongan Bintara (Bripda s.d. Aiptu) dan memiliki sertifikasi/dikjur Penyidik.',
                ],
            ],
            [
                'position' => [
                    'code' => 'KANIT_RESKRIM',
                    'title' => 'Kanit Reskrim Polsek',
                    'unit_name' => 'Unit Reskrim Polsek',
                    'quota' => 3,
                    'description' => 'Kepala unit operasional penyidikan dan penindakan kriminal di Polsek.',
                    'is_active' => true,
                ],
                'rule' => [
                    'min_rank_id' => $iptu?->id,
                    'required_rank_id' => null,
                    'required_rank_category' => null,
                    'min_education_level' => 'S1',
                    'requires_sespimmen' => false,
                    'required_certification_keyword' => 'Penyidik',
                    'required_past_position_title' => null,
                    'required_function_track' => null,
                    'notes' => 'Min. Iptu, Min. S1, dan memiliki sertifikasi/dikjur Penyidik.',
                ],
            ],
            [
                'position' => [
                    'code' => 'BINTARA_BHABINKAMTIBMAS',
                    'title' => 'Bintara Bhabinkamtibmas',
                    'unit_name' => 'Satbinmas / Polsek',
                    'quota' => 5,
                    'description' => 'Bhayangkara Pembina Keamanan dan Ketertiban Masyarakat di desa/kelurahan.',
                    'is_active' => true,
                ],
                'rule' => [
                    'min_rank_id' => null,
                    'required_rank_id' => null,
                    'required_rank_category' => 'bintara',
                    'min_education_level' => null,
                    'requires_sespimmen' => false,
                    'required_certification_keyword' => 'Bhabinkamtibmas',
                    'required_past_position_title' => null,
                    'required_function_track' => null,
                    'notes' => 'Golongan Bintara dan memiliki sertifikasi Bhabinkamtibmas.',
                ],
            ],
        ];

        $criteria = Criterion::all();

        foreach ($positionsData as $data) {
            /** @var Position $position */
            $position = Position::query()->updateOrCreate(
                ['code' => $data['position']['code']],
                $data['position']
            );

            // Seed Rule
            $ruleData = $data['rule'];
            $ruleData['position_id'] = $position->id;
            PositionRule::query()->updateOrCreate(
                ['position_id' => $position->id],
                $ruleData
            );

            // Seed Criteria Weights (default weights from criteria table)
            foreach ($criteria as $criterion) {
                PositionCriterion::query()->updateOrCreate(
                    [
                        'position_id' => $position->id,
                        'criterion_id' => $criterion->id,
                    ],
                    [
                        'weight' => $criterion->default_weight,
                    ]
                );
            }
        }
    }
}
