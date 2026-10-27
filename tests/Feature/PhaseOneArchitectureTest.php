<?php

namespace Tests\Feature;

use App\Models\Criterion;
use App\Models\Personnel;
use App\Models\Position;
use App\Models\Rank;
use App\Repositories\Contracts\CriterionRepositoryInterface;
use App\Repositories\Contracts\PersonnelRepositoryInterface;
use App\Repositories\Contracts\PositionRepositoryInterface;
use App\Repositories\Contracts\RankRepositoryInterface;
use App\Repositories\Contracts\TopsisRunRepositoryInterface;
use App\Repositories\Eloquent\CriterionRepository;
use App\Repositories\Eloquent\PersonnelRepository;
use App\Repositories\Eloquent\PositionRepository;
use App\Repositories\Eloquent\RankRepository;
use App\Repositories\Eloquent\TopsisRunRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PhaseOneArchitectureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_repository_interfaces_are_bound_in_container(): void
    {
        $this->assertInstanceOf(
            RankRepository::class,
            app(RankRepositoryInterface::class)
        );
        $this->assertInstanceOf(
            PositionRepository::class,
            app(PositionRepositoryInterface::class)
        );
        $this->assertInstanceOf(
            CriterionRepository::class,
            app(CriterionRepositoryInterface::class)
        );
        $this->assertInstanceOf(
            PersonnelRepository::class,
            app(PersonnelRepositoryInterface::class)
        );
        $this->assertInstanceOf(
            TopsisRunRepository::class,
            app(TopsisRunRepositoryInterface::class)
        );
    }

    public function test_all_models_use_valid_uuid(): void
    {
        $rank = Rank::query()->first();
        $this->assertNotNull($rank);
        $this->assertTrue(Str::isUuid($rank->id));

        $position = Position::query()->first();
        $this->assertNotNull($position);
        $this->assertTrue(Str::isUuid($position->id));

        $criterion = Criterion::query()->first();
        $this->assertNotNull($criterion);
        $this->assertTrue(Str::isUuid($criterion->id));

        $personnel = Personnel::query()->first();
        $this->assertNotNull($personnel);
        $this->assertTrue(Str::isUuid($personnel->id));
    }

    public function test_api_can_list_positions(): void
    {
        $response = $this->getJson('/api/v1/positions');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => ['id', 'code', 'title', 'quota', 'is_active'],
                ],
            ]);
    }

    public function test_api_can_list_criteria(): void
    {
        $response = $this->getJson('/api/v1/criteria');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => ['id', 'code', 'name', 'type', 'default_weight'],
                ],
            ]);
    }

    public function test_api_can_show_personnel_with_full_profile(): void
    {
        $personnel = Personnel::query()->first();
        $this->assertNotNull($personnel);

        $response = $this->getJson('/api/v1/personnel/'.$personnel->id);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.id', $personnel->id)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'id',
                    'nrp',
                    'name',
                    'rank',
                    'certifications',
                    'career_histories',
                    'assessment_scores',
                ],
            ]);
    }

    public function test_api_validates_position_criteria_weights_must_sum_to_one(): void
    {
        $position = Position::query()->first();
        $this->assertNotNull($position);

        $criteria = Criterion::query()->take(2)->get();
        $this->assertCount(2, $criteria);

        // Invalid weight sum: 0.3 + 0.3 = 0.6 != 1.0 -> should fail validation (422)
        $invalidPayload = [
            'weights' => [
                ['criterion_id' => $criteria[0]->id, 'weight' => 0.3],
                ['criterion_id' => $criteria[1]->id, 'weight' => 0.3],
            ],
        ];

        $response = $this->postJson("/api/v1/positions/{$position->id}/weights", $invalidPayload);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['weights']);

        // Valid weight sum: 0.5 + 0.5 = 1.0 -> should pass
        $validPayload = [
            'weights' => [
                ['criterion_id' => $criteria[0]->id, 'weight' => 0.5],
                ['criterion_id' => $criteria[1]->id, 'weight' => 0.5],
            ],
        ];

        $validResponse = $this->postJson("/api/v1/positions/{$position->id}/weights", $validPayload);
        $validResponse->assertStatus(200)
            ->assertJsonPath('status', 'success');
    }

    public function test_api_can_record_assessment_score(): void
    {
        $personnel = Personnel::query()->first();
        $criterion = Criterion::query()->first();

        $payload = [
            'personnel_id' => $personnel->id,
            'criterion_id' => $criterion->id,
            'score' => 88.5,
            'period_year' => 2026,
            'notes' => 'Nilai pengujian',
        ];

        $response = $this->postJson('/api/v1/assessment-scores', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.score', 88.5);
    }
}
