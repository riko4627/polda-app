<?php

namespace App\Services;

use App\DTOs\TopsisCalculationResult;
use App\DTOs\TopsisCandidateScore;
use App\DTOs\TopsisCriterionConfig;
use App\DTOs\TopsisEngineOutput;
use InvalidArgumentException;

class TopsisEngineService
{
    /**
     * Menjalankan kalkulasi TOPSIS secara matematis lengkap.
     *
     * @param  array<int, TopsisCandidateScore>  $candidates  Daftar kandidat beserta skor mentah per kriteria
     * @param  array<int, TopsisCriterionConfig>  $criteria  Daftar kriteria beserta bobot dan tipe (benefit/cost)
     */
    public function calculate(array $candidates, array $criteria): TopsisEngineOutput
    {
        if (count($candidates) === 0) {
            throw new InvalidArgumentException('Kalkulasi TOPSIS membutuhkan minimal 1 kandidat.');
        }

        if (count($criteria) === 0) {
            throw new InvalidArgumentException('Kalkulasi TOPSIS membutuhkan minimal 1 kriteria penilaian.');
        }

        // 1. Matriks Keputusan Mentah (X)
        $matrixX = [];
        foreach ($candidates as $candidate) {
            $matrixX[$candidate->personnelId] = [];
            foreach ($criteria as $criterion) {
                $matrixX[$candidate->personnelId][$criterion->criterionId] = (float) ($candidate->scores[$criterion->criterionId] ?? 0.0);
            }
        }

        // 2. Normalisasi Matriks (R) menggunakan Vektor Euclidean
        // r_ij = x_ij / sqrt(sum(x_kj^2))
        $divisors = [];
        foreach ($criteria as $criterion) {
            $sumSquares = 0.0;
            foreach ($candidates as $candidate) {
                $val = $matrixX[$candidate->personnelId][$criterion->criterionId];
                $sumSquares += ($val * $val);
            }
            $divisors[$criterion->criterionId] = sqrt($sumSquares);
        }

        $matrixR = [];
        foreach ($candidates as $candidate) {
            $matrixR[$candidate->personnelId] = [];
            foreach ($criteria as $criterion) {
                $cId = $criterion->criterionId;
                $divisor = $divisors[$cId];
                // Penanganan pembagian dengan nol
                $matrixR[$candidate->personnelId][$cId] = ($divisor > 0.0)
                    ? ($matrixX[$candidate->personnelId][$cId] / $divisor)
                    : 0.0;
            }
        }

        // 3. Matriks Ternormalisasi Terbobot (Y)
        // y_ij = w_j * r_ij
        $matrixY = [];
        foreach ($candidates as $candidate) {
            $matrixY[$candidate->personnelId] = [];
            foreach ($criteria as $criterion) {
                $cId = $criterion->criterionId;
                $matrixY[$candidate->personnelId][$cId] = $matrixR[$candidate->personnelId][$cId] * $criterion->weight;
            }
        }

        // 4. Solusi Ideal Positif (A+) dan Solusi Ideal Negatif (A-)
        $idealPositive = [];
        $idealNegative = [];

        foreach ($criteria as $criterion) {
            $cId = $criterion->criterionId;
            $columnValues = [];
            foreach ($candidates as $candidate) {
                $columnValues[] = $matrixY[$candidate->personnelId][$cId];
            }

            $maxVal = max($columnValues);
            $minVal = min($columnValues);

            if ($criterion->isBenefit()) {
                $idealPositive[$cId] = $maxVal;
                $idealNegative[$cId] = $minVal;
            } else {
                // Cost: terbalik (nilai terendah adalah yang terbaik)
                $idealPositive[$cId] = $minVal;
                $idealNegative[$cId] = $maxVal;
            }
        }

        // 5. Jarak Solusi Euclidean (D+ dan D-) & Preferensi (V)
        $rawResults = [];
        foreach ($candidates as $candidate) {
            $pId = $candidate->personnelId;

            $sumSquareDiffPlus = 0.0;
            $sumSquareDiffMinus = 0.0;

            foreach ($criteria as $criterion) {
                $cId = $criterion->criterionId;
                $yVal = $matrixY[$pId][$cId];

                $diffPlus = $yVal - $idealPositive[$cId];
                $diffMinus = $yVal - $idealNegative[$cId];

                $sumSquareDiffPlus += ($diffPlus * $diffPlus);
                $sumSquareDiffMinus += ($diffMinus * $diffMinus);
            }

            $dPlus = sqrt($sumSquareDiffPlus);
            $dMinus = sqrt($sumSquareDiffMinus);

            // 6. Nilai Preferensi Akhir (V_i = D_i^- / (D_i^+ + D_i^-))
            $sumD = $dPlus + $dMinus;
            if ($sumD > 0.0) {
                $preferenceScore = $dMinus / $sumD;
            } else {
                // Kasus jika kandidat hanya 1 atau seluruh kandidat bernilai identik
                $preferenceScore = ($dPlus == 0.0) ? 1.0 : 0.5;
            }

            $rawResults[] = [
                'personnel_id' => $pId,
                'd_plus' => round($dPlus, 6),
                'd_minus' => round($dMinus, 6),
                'preference_score' => round($preferenceScore, 6),
                'normalized_scores' => $matrixR[$pId],
                'weighted_scores' => $matrixY[$pId],
            ];
        }

        // 7. Pengurutan Perankingan (Descending by preference score)
        usort($rawResults, function (array $a, array $b): int {
            if ($b['preference_score'] === $a['preference_score']) {
                // Tie breaker: jarak terjauh dari negatif (d_minus terbesar)
                return $b['d_minus'] <=> $a['d_minus'];
            }

            return $b['preference_score'] <=> $a['preference_score'];
        });

        // 8. Bentuk DTO TopsisCalculationResult dengan nomor ranking
        $rankedResults = [];
        $rankNumber = 1;
        foreach ($rawResults as $item) {
            $rankedResults[] = new TopsisCalculationResult(
                personnelId: $item['personnel_id'],
                dPlus: $item['d_plus'],
                dMinus: $item['d_minus'],
                preferenceScore: $item['preference_score'],
                rank: $rankNumber++,
                normalizedScores: $item['normalized_scores'],
                weightedScores: $item['weighted_scores']
            );
        }

        return new TopsisEngineOutput(
            matrixX: $matrixX,
            matrixR: $matrixR,
            matrixY: $matrixY,
            idealPositive: $idealPositive,
            idealNegative: $idealNegative,
            candidateResults: $rankedResults
        );
    }
}
