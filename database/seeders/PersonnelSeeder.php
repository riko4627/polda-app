<?php

namespace Database\Seeders;

use App\Models\AssessmentScore;
use App\Models\CareerHistory;
use App\Models\Criterion;
use App\Models\Personnel;
use App\Models\PersonnelCertification;
use App\Models\Rank;
use Illuminate\Database\Seeder;

class PersonnelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ranks = Rank::all()->keyBy('code');
        $criteria = Criterion::all()->keyBy('code');

        $personnelList = [
            [
                'nrp' => '78010123',
                'name' => 'Budi Santoso, S.I.K., M.Si.',
                'rank_id' => $ranks['AKBP']->id,
                'education_level' => 'S2',
                'education_major' => 'Kajian Ilmu Kepolisian',
                'has_sespimmen' => true,
                'current_position' => 'Kasubdit 3 Ditreskrimum',
                'unit_name' => 'Ditreskrimum Polda',
                'function_track' => 'Reskrim',
                'phone' => '081234567890',
                'email' => 'budi.santoso@polri.go.id',
                'histories' => [
                    [
                        'position_title' => 'Kapolres Sleman',
                        'unit_name' => 'Polres Sleman',
                        'function_track' => 'Operasional',
                        'start_date' => '2022-01-15',
                        'end_date' => '2024-02-01',
                        'sk_number' => 'KEP/12/I/2022',
                    ],
                    [
                        'position_title' => 'Kasat Reskrim Polresta',
                        'unit_name' => 'Polresta Yogyakarta',
                        'function_track' => 'Reskrim',
                        'start_date' => '2019-03-01',
                        'end_date' => '2021-12-15',
                        'sk_number' => 'KEP/88/III/2019',
                    ],
                ],
                'certifications' => [
                    ['certification_name' => 'Sertifikasi Penyidik Utama', 'institution' => 'Lemdiklat Polri', 'issue_date' => '2020-05-10'],
                    ['certification_name' => 'Dikjur Tipikor Lanjutan', 'institution' => 'KPK & Polri', 'issue_date' => '2021-08-20'],
                ],
                'scores' => ['C1' => 92.50, 'C2' => 88.00, 'C3' => 84.00, 'C4' => 85.00, 'C5' => 90.00, 'C6' => 0.00],
            ],
            [
                'nrp' => '79040456',
                'name' => 'Hendra Gunawan, S.H., S.I.K.',
                'rank_id' => $ranks['AKBP']->id,
                'education_level' => 'S2',
                'education_major' => 'Hukum Pidana',
                'has_sespimmen' => true,
                'current_position' => 'Kasubdit Gakkum Ditlantas',
                'unit_name' => 'Ditlantas Polda',
                'function_track' => 'Lantas',
                'phone' => '081234567891',
                'email' => 'hendra.gunawan@polri.go.id',
                'histories' => [
                    [
                        'position_title' => 'Wakapolres Magelang',
                        'unit_name' => 'Polres Magelang',
                        'function_track' => 'Operasional',
                        'start_date' => '2021-06-01',
                        'end_date' => '2023-08-15',
                        'sk_number' => 'KEP/210/VI/2021',
                    ],
                ],
                'certifications' => [
                    ['certification_name' => 'Sertifikasi Asesor SDM Polri', 'institution' => 'Biro SDM', 'issue_date' => '2022-03-15'],
                ],
                'scores' => ['C1' => 88.00, 'C2' => 85.00, 'C3' => 80.00, 'C4' => 82.00, 'C5' => 78.00, 'C6' => 5.00],
            ],
            [
                'nrp' => '80050789',
                'name' => 'Agus Wijaya, S.I.K.',
                'rank_id' => $ranks['AKBP']->id,
                'education_level' => 'S1',
                'education_major' => 'Ilmu Kepolisian',
                'has_sespimmen' => false,
                'current_position' => 'Kabag Ops Polres',
                'unit_name' => 'Polres Bantul',
                'function_track' => 'Reskrim',
                'phone' => '081234567892',
                'email' => 'agus.wijaya@polri.go.id',
                'histories' => [],
                'certifications' => [
                    ['certification_name' => 'Sertifikasi Penyidik Madya', 'institution' => 'Lemdiklat Polri', 'issue_date' => '2019-10-10'],
                ],
                'scores' => ['C1' => 82.00, 'C2' => 78.00, 'C3' => 85.00, 'C4' => 72.00, 'C5' => 70.00, 'C6' => 0.00],
            ],
            [
                'nrp' => '82060111',
                'name' => 'Tri Prabowo, S.H.',
                'rank_id' => $ranks['KOMPOL']->id,
                'education_level' => 'S1',
                'education_major' => 'Hukum',
                'has_sespimmen' => false,
                'current_position' => 'Kapolsek Kasihan',
                'unit_name' => 'Polsek Kasihan',
                'function_track' => 'Samapta',
                'phone' => '081234567893',
                'email' => 'tri.prabowo@polri.go.id',
                'histories' => [],
                'certifications' => [],
                'scores' => ['C1' => 85.00, 'C2' => 80.00, 'C3' => 78.00, 'C4' => 76.00, 'C5' => 72.00, 'C6' => 0.00],
            ],
            [
                'nrp' => '85070222',
                'name' => 'Dian Purnama, S.H.',
                'rank_id' => $ranks['AKP']->id,
                'education_level' => 'S1',
                'education_major' => 'Ilmu Hukum',
                'has_sespimmen' => false,
                'current_position' => 'Kanit Idik Satreskrim',
                'unit_name' => 'Polresta Sleman',
                'function_track' => 'Reskrim',
                'phone' => '081234567894',
                'email' => 'dian.purnama@polri.go.id',
                'histories' => [],
                'certifications' => [
                    ['certification_name' => 'Sertifikasi Penyidik Madya', 'institution' => 'Lemdiklat Polri', 'issue_date' => '2021-04-12'],
                ],
                'scores' => ['C1' => 89.00, 'C2' => 86.00, 'C3' => 88.00, 'C4' => 82.00, 'C5' => 85.00, 'C6' => 0.00],
            ],
            [
                'nrp' => '91080333',
                'name' => 'Rian Anggoro, S.Tr.K.',
                'rank_id' => $ranks['IPTU']->id,
                'education_level' => 'S1',
                'education_major' => 'Terapan Kepolisian',
                'has_sespimmen' => false,
                'current_position' => 'Kanit Reskrim Polsek Sewon',
                'unit_name' => 'Polsek Sewon',
                'function_track' => 'Reskrim',
                'phone' => '081234567895',
                'email' => 'rian.anggoro@polri.go.id',
                'histories' => [],
                'certifications' => [
                    ['certification_name' => 'Sertifikasi Penyidik', 'institution' => 'Pusdik Reskrim Megamendung', 'issue_date' => '2022-09-01'],
                ],
                'scores' => ['C1' => 86.50, 'C2' => 83.00, 'C3' => 90.00, 'C4' => 75.00, 'C5' => 80.00, 'C6' => 0.00],
            ],
            [
                'nrp' => '95090444',
                'name' => 'Farhan Ramadhan',
                'rank_id' => $ranks['IPDA']->id,
                'education_level' => 'SMA',
                'education_major' => null,
                'has_sespimmen' => false,
                'current_position' => 'Pama Ditreskrimum',
                'unit_name' => 'Ditreskrimum Polda',
                'function_track' => 'Reskrim',
                'phone' => '081234567896',
                'email' => 'farhan.ramadhan@polri.go.id',
                'histories' => [],
                'certifications' => [],
                'scores' => ['C1' => 80.00, 'C2' => 75.00, 'C3' => 86.00, 'C4' => 60.00, 'C5' => 62.00, 'C6' => 0.00],
            ],
            [
                'nrp' => '77100555',
                'name' => 'Bambang Sutrisno',
                'rank_id' => $ranks['AIPTU']->id,
                'education_level' => 'SMA',
                'education_major' => null,
                'has_sespimmen' => false,
                'current_position' => 'Bintara Penyidik Unit Reskrim',
                'unit_name' => 'Polsek Depok Timur',
                'function_track' => 'Reskrim',
                'phone' => '081234567897',
                'email' => 'bambang.sutrisno@polri.go.id',
                'histories' => [],
                'certifications' => [
                    ['certification_name' => 'Dikjur Penyidik Pembantu', 'institution' => 'SPN Selopamioro', 'issue_date' => '2018-07-20'],
                ],
                'scores' => ['C1' => 87.00, 'C2' => 81.00, 'C3' => 76.00, 'C4' => 90.00, 'C5' => 82.00, 'C6' => 0.00],
            ],
            [
                'nrp' => '88110666',
                'name' => 'Eko Setiawan',
                'rank_id' => $ranks['BRIPKA']->id,
                'education_level' => 'D3',
                'education_major' => 'Manajemen Informatika',
                'has_sespimmen' => false,
                'current_position' => 'Bintara Unit Reskrim',
                'unit_name' => 'Satreskrim Polresta',
                'function_track' => 'Reskrim',
                'phone' => '081234567898',
                'email' => 'eko.setiawan@polri.go.id',
                'histories' => [],
                'certifications' => [
                    ['certification_name' => 'Dikjur Penyidik Cyber', 'institution' => 'Pusdik Reskrim', 'issue_date' => '2020-11-15'],
                ],
                'scores' => ['C1' => 85.00, 'C2' => 84.00, 'C3' => 85.00, 'C4' => 78.00, 'C5' => 76.00, 'C6' => 0.00],
            ],
            [
                'nrp' => '92030888',
                'name' => 'Aji Saputra, S.Pd.',
                'rank_id' => $ranks['BRIGPOL']->id,
                'education_level' => 'S1',
                'education_major' => 'Pendidikan',
                'has_sespimmen' => false,
                'current_position' => 'Bhabinkamtibmas Kelurahan Caturtunggal',
                'unit_name' => 'Polsek Depok Barat',
                'function_track' => 'Binmas',
                'phone' => '081234567899',
                'email' => 'aji.saputra@polri.go.id',
                'histories' => [],
                'certifications' => [
                    ['certification_name' => 'Sertifikasi Pelatihan Bhabinkamtibmas Polmas', 'institution' => 'Ditbinmas Polda', 'issue_date' => '2021-02-14'],
                ],
                'scores' => ['C1' => 88.00, 'C2' => 85.00, 'C3' => 85.00, 'C4' => 75.00, 'C5' => 80.00, 'C6' => 0.00],
            ],
            [
                'nrp' => '01120777',
                'name' => 'Rizky Pratama',
                'rank_id' => $ranks['BRIPDA']->id,
                'education_level' => 'SMA',
                'education_major' => null,
                'has_sespimmen' => false,
                'current_position' => 'Bintara Remaja Satbinmas',
                'unit_name' => 'Polresta Yogyakarta',
                'function_track' => 'Binmas',
                'phone' => '081234567800',
                'email' => 'rizky.pratama@polri.go.id',
                'histories' => [],
                'certifications' => [
                    ['certification_name' => 'Sertifikasi Dasar Bhabinkamtibmas', 'institution' => 'SPN Selopamioro', 'issue_date' => '2023-01-10'],
                ],
                'scores' => ['C1' => 82.00, 'C2' => 80.00, 'C3' => 92.00, 'C4' => 60.00, 'C5' => 65.00, 'C6' => 0.00],
            ],
        ];

        foreach ($personnelList as $pData) {
            /** @var Personnel $personnel */
            $personnel = Personnel::query()->updateOrCreate(
                ['nrp' => $pData['nrp']],
                [
                    'name' => $pData['name'],
                    'rank_id' => $pData['rank_id'],
                    'education_level' => $pData['education_level'],
                    'education_major' => $pData['education_major'],
                    'has_sespimmen' => $pData['has_sespimmen'],
                    'current_position' => $pData['current_position'],
                    'unit_name' => $pData['unit_name'],
                    'function_track' => $pData['function_track'],
                    'phone' => $pData['phone'],
                    'email' => $pData['email'],
                    'status' => 'active',
                ]
            );

            // Career Histories
            foreach ($pData['histories'] as $history) {
                CareerHistory::query()->updateOrCreate(
                    [
                        'personnel_id' => $personnel->id,
                        'position_title' => $history['position_title'],
                    ],
                    $history
                );
            }

            // Certifications
            foreach ($pData['certifications'] as $cert) {
                PersonnelCertification::query()->updateOrCreate(
                    [
                        'personnel_id' => $personnel->id,
                        'certification_name' => $cert['certification_name'],
                    ],
                    $cert
                );
            }

            // Scores for period_year = 2026
            foreach ($pData['scores'] as $criterionCode => $score) {
                if (isset($criteria[$criterionCode])) {
                    AssessmentScore::query()->updateOrCreate(
                        [
                            'personnel_id' => $personnel->id,
                            'criterion_id' => $criteria[$criterionCode]->id,
                            'period_year' => 2026,
                        ],
                        [
                            'score' => $score,
                            'notes' => 'Penilaian berkala periode 2026',
                        ]
                    );
                }
            }
        }
    }
}
