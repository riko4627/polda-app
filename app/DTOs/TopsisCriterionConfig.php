<?php

namespace App\DTOs;

readonly class TopsisCriterionConfig
{
    public function __construct(
        public string $criterionId,
        public string $code,
        public string $name,
        public string $type, // 'benefit' | 'cost'
        public float $weight
    ) {}

    public function isBenefit(): bool
    {
        return strtolower($this->type) === 'benefit';
    }

    public function isCost(): bool
    {
        return strtolower($this->type) === 'cost';
    }
}
