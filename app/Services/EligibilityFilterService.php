<?php

namespace App\Services;

use App\DTOs\EligibilityCheckResult;
use App\Models\Personnel;
use App\Models\PositionRule;

class EligibilityFilterService
{
    /**
     * Peta hierarki tingkat pendidikan formal.
     *
     * @var array<string, int>
     */
    protected array $educationHierarchy = [
        'SMA' => 1,
        'SMK' => 1,
        'D1' => 2,
        'D2' => 2,
        'D3' => 3,
        'D4' => 4,
        'S1' => 4,
        'S2' => 5,
        'S3' => 6,
    ];

    /**
     * Evaluasi kelayakan personel terhadap aturan mutlak jabatan.
     */
    public function evaluate(Personnel $personnel, ?PositionRule $rule): EligibilityCheckResult
    {
        // Jika jabatan tidak memiliki aturan syarat mutlak, personel otomatis MS
        if (! $rule) {
            return EligibilityCheckResult::eligible();
        }

        $reasons = [];

        // Pastikan relasi rank termuat
        $personnelRank = $personnel->rank;

        // 1. Validasi Pangkat Wajib Mutlak (Exact Match)
        if ($rule->required_rank_id && $rule->requiredRank) {
            if ($personnel->rank_id !== $rule->required_rank_id) {
                $reasons[] = sprintf(
                    'Pangkat wajib adalah %s, sedangkan pangkat personel saat ini adalah %s.',
                    $rule->requiredRank->name.' ('.$rule->requiredRank->code.')',
                    $personnelRank?->name ?? 'Tidak Terdefinisi'
                );
            }
        }

        // 2. Validasi Batas Minimal Pangkat (Level Order >= Min Rank Level Order)
        if ($rule->min_rank_id && $rule->minRank && $personnelRank) {
            if ($personnelRank->level_order < $rule->minRank->level_order) {
                $reasons[] = sprintf(
                    'Pangkat personel saat ini (%s) belum memenuhi batas minimal pangkat yang dipersyaratkan (%s).',
                    $personnelRank->code,
                    $rule->minRank->code
                );
            }
        }

        // 3. Validasi Golongan / Kategori Pangkat (Tamtama, Bintara, Pama, Pamen, Pati)
        if ($rule->required_rank_category && $personnelRank) {
            if (strtolower($personnelRank->category) !== strtolower($rule->required_rank_category)) {
                $reasons[] = sprintf(
                    'Golongan kepangkatan wajib %s, sedangkan personel saat ini tergolong %s.',
                    strtoupper($rule->required_rank_category),
                    strtoupper($personnelRank->category)
                );
            }
        }

        // 4. Validasi Dikbang Sespimmen Polri
        if ($rule->requires_sespimmen && ! $personnel->has_sespimmen) {
            $reasons[] = 'Personel belum menempuh atau lulus Pendidikan Pengembangan Sespimmen Polri.';
        }

        // 5. Validasi Minimal Tingkat Pendidikan Formal
        if ($rule->min_education_level) {
            $requiredEduLevel = strtoupper(trim($rule->min_education_level));
            $personnelEduLevel = strtoupper(trim($personnel->education_level));

            $requiredWeight = $this->educationHierarchy[$requiredEduLevel] ?? 0;
            $personnelWeight = $this->educationHierarchy[$personnelEduLevel] ?? 0;

            if ($personnelWeight < $requiredWeight) {
                $reasons[] = sprintf(
                    'Pendidikan formal terakhir (%s) belum memenuhi kualifikasi minimal %s.',
                    $personnel->education_level,
                    $rule->min_education_level
                );
            }
        }

        // 6. Validasi Sertifikasi / Dikjur Khusus
        if ($rule->required_certification_keyword) {
            $keyword = strtolower(trim($rule->required_certification_keyword));
            $hasCertification = $personnel->certifications->contains(function ($cert) use ($keyword) {
                return str_contains(strtolower($cert->certification_name), $keyword)
                    || str_contains(strtolower($cert->institution ?? ''), $keyword);
            });

            if (! $hasCertification) {
                $reasons[] = sprintf(
                    "Belum memiliki sertifikasi atau dikjur kejuruan dengan spesifikasi '%s'.",
                    $rule->required_certification_keyword
                );
            }
        }

        // 7. Validasi Riwayat Jabatan Terdahulu (Past Position)
        if ($rule->required_past_position_title) {
            $requiredPosition = trim($rule->required_past_position_title);
            $hasHeldPosition = $personnel->careerHistories->contains(function ($history) use ($requiredPosition) {
                return $this->matchesPositionTitle($history->position_title, $requiredPosition);
            });

            if (! $hasHeldPosition) {
                $reasons[] = sprintf(
                    "Wajib memiliki rekam jejak pernah menduduki jabatan '%s'.",
                    $rule->required_past_position_title
                );
            }
        }

        // 8. Validasi Rekam Jejak Jalur Fungsi (Function Track)
        if ($rule->required_function_track) {
            $requiredTrack = strtolower(trim($rule->required_function_track));
            $currentTrackMatch = str_contains(strtolower($personnel->function_track), $requiredTrack);
            $historyTrackMatch = $personnel->careerHistories->contains(function ($history) use ($requiredTrack) {
                return str_contains(strtolower($history->function_track ?? ''), $requiredTrack)
                    || str_contains(strtolower($history->position_title), $requiredTrack);
            });

            if (! $currentTrackMatch && ! $historyTrackMatch) {
                $reasons[] = sprintf(
                    'Wajib memiliki rekam jejak tugas di lingkungan fungsi %s.',
                    $rule->required_function_track
                );
            }
        }

        if (count($reasons) > 0) {
            return EligibilityCheckResult::ineligible($reasons);
        }

        return EligibilityCheckResult::eligible();
    }

    /**
     * Memeriksa apakah riwayat jabatan cocok dengan jabatan yang disyaratkan.
     * Mencegah kecocokan semu seperti 'Wakapolres' dianggap sebagai 'Kapolres'.
     */
    protected function matchesPositionTitle(string $actualTitle, string $requiredTitle): bool
    {
        $actual = strtolower(trim($actualTitle));
        $required = strtolower(trim($requiredTitle));

        // Jika jabatan yang disyaratkan bukan jabatan wakil (misal: 'Kapolres'),
        // maka jabatan wakil (misal: 'Wakapolres', 'Waka Kapolres', 'Wakil Kapolres') tidak memenuhi syarat.
        if (! str_contains($required, 'waka') && ! str_contains($required, 'wakil')) {
            if (str_starts_with($actual, 'waka') || str_starts_with($actual, 'wakil')) {
                return false;
            }
        }

        return (bool) preg_match('/\b'.preg_quote($required, '/').'\b/i', $actual);
    }
}
