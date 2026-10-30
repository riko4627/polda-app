<?php

namespace App\DTOs;

readonly class TopsisCalculationResult
{
    /**
     * @param  array<string, float>  $normalizedScores
     * @param  array<string, float>  $weightedScores
     */
    public function __construct(
        public string $personnelId,
        public float $dPlus,
        public float $dMinus,
        public float $preferenceScore,
        public int $rank,
        public array $normalizedScores,
        public array $weightedScores
    ) {}
}
