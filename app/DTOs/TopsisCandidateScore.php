<?php

namespace App\DTOs;

readonly class TopsisCandidateScore
{
    /**
     * @param  array<string, float>  $scores  [criterion_id => score]
     */
    public function __construct(
        public string $personnelId,
        public array $scores
    ) {}
}
