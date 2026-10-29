<?php

namespace App\DTOs;

readonly class EligibilityCheckResult
{
    /**
     * @param  array<int, string>  $reasons
     */
    public function __construct(
        public bool $isEligible,
        public array $reasons = []
    ) {}

    public static function eligible(): self
    {
        return new self(isEligible: true, reasons: []);
    }

    /**
     * @param  array<int, string>  $reasons
     */
    public static function ineligible(array $reasons): self
    {
        return new self(isEligible: false, reasons: $reasons);
    }
}
