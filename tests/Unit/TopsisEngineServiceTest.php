<?php

namespace Tests\Unit;

use App\DTOs\TopsisCandidateScore;
use App\DTOs\TopsisCriterionConfig;
use App\Services\TopsisEngineService;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class TopsisEngineServiceTest extends TestCase
{
    protected TopsisEngineService $engine;

    protected function setUp(): void
    {
        parent::setUp();
        $this->engine = new TopsisEngineService;
    }

    public function test_topsis_accurately_ranks_better_candidate_first(): void
    {
        $criteria = [
            new TopsisCriterionConfig('c1', 'C1', 'SMK', 'benefit', 0.6),
            new TopsisCriterionConfig('c2', 'C2', 'Pelanggaran', 'cost', 0.4),
        ];

        $candidates = [
            new TopsisCandidateScore('cand_superior', ['c1' => 95.0, 'c2' => 0.0]),
            new TopsisCandidateScore('cand_inferior', ['c1' => 60.0, 'c2' => 30.0]),
        ];

        $output = $this->engine->calculate($candidates, $criteria);

        $this->assertCount(2, $output->candidateResults);

        $rank1 = $output->candidateResults[0];
        $rank2 = $output->candidateResults[1];

        $this->assertEquals('cand_superior', $rank1->personnelId);
        $this->assertEquals(1, $rank1->rank);

        $this->assertEquals('cand_inferior', $rank2->personnelId);
        $this->assertEquals(2, $rank2->rank);

        $this->assertGreaterThan($rank2->preferenceScore, $rank1->preferenceScore);
        $this->assertGreaterThanOrEqual(0.0, $rank1->preferenceScore);
        $this->assertLessThanOrEqual(1.0, $rank1->preferenceScore);
    }

    public function test_topsis_zero_division_protection(): void
    {
        $criteria = [
            new TopsisCriterionConfig('c1', 'C1', 'SMK', 'benefit', 0.5),
            new TopsisCriterionConfig('c2', 'C2', 'Pelanggaran', 'cost', 0.5),
        ];

        // Semua kandidat memiliki nilai 0 pada kriteria c2 (sum of squares = 0)
        $candidates = [
            new TopsisCandidateScore('p1', ['c1' => 85.0, 'c2' => 0.0]),
            new TopsisCandidateScore('p2', ['c1' => 90.0, 'c2' => 0.0]),
        ];

        $output = $this->engine->calculate($candidates, $criteria);

        $this->assertCount(2, $output->candidateResults);
        $this->assertEquals('p2', $output->candidateResults[0]->personnelId);
        $this->assertEquals(0.0, $output->matrixR['p1']['c2']);
        $this->assertEquals(0.0, $output->matrixR['p2']['c2']);
    }

    public function test_topsis_handles_single_candidate(): void
    {
        $criteria = [
            new TopsisCriterionConfig('c1', 'C1', 'SMK', 'benefit', 1.0),
        ];

        $candidates = [
            new TopsisCandidateScore('single_person', ['c1' => 88.0]),
        ];

        $output = $this->engine->calculate($candidates, $criteria);

        $this->assertCount(1, $output->candidateResults);
        $this->assertEquals(1, $output->candidateResults[0]->rank);
        $this->assertEquals(1.0, $output->candidateResults[0]->preferenceScore);
    }

    public function test_topsis_throws_exception_on_empty_candidates(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $criteria = [
            new TopsisCriterionConfig('c1', 'C1', 'SMK', 'benefit', 1.0),
        ];

        $this->engine->calculate([], $criteria);
    }
}
