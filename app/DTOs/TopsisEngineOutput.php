<?php

namespace App\DTOs;

readonly class TopsisEngineOutput
{
    /**
     * @param  array<string, array<string, float>>  $matrixX  [personnel_id => [criterion_id => score]]
     * @param  array<string, array<string, float>>  $matrixR  [personnel_id => [criterion_id => normalized]]
     * @param  array<string, array<string, float>>  $matrixY  [personnel_id => [criterion_id => weighted]]
     * @param  array<string, float>  $idealPositive  [criterion_id => value]
     * @param  array<string, float>  $idealNegative  [criterion_id => value]
     * @param  array<int, TopsisCalculationResult>  $candidateResults
     */
    public function __construct(
        public array $matrixX,
        public array $matrixR,
        public array $matrixY,
        public array $idealPositive,
        public array $idealNegative,
        public array $candidateResults
    ) {}
}
