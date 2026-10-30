<?php

namespace Tests\Feature;

use App\Models\Position;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SPKRecommendationFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_can_run_recommendation_for_kapolsek_successfully(): void
    {
        $position = Position::query()->where('code', 'KAPOLSEK')->first();
        $this->assertNotNull($position);

        $payload = [
            'position_id' => $position->id,
            'period_year' => 2026,
            'notes' => 'Simulasi Sidang Wanjak',
        ];

        $response = $this->postJson('/api/v1/recommendations/run', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'run_info' => [
                        'id',
                        'position',
                        'period_year',
                        'total_candidates',
                        'eligible_candidates',
                        'ineligible_candidates',
                        'status',
                    ],
                    'tab_1_ranked_recommendations',
                    'tab_2_ineligible_personnel',
                    'tab_3_mathematical_matrices',
                ],
            ]);

        // Verifikasi bahwa ada kandidat lolos (Pama Iptu/AKP/Kompol/AKBP lolos syarat mutlak Kapolsek)
        $eligibleCount = count($response->json('data.tab_1_ranked_recommendations'));
        $this->assertGreaterThan(0, $eligibleCount);

        // Verifikasi kandidat pertama memiliki rank 1
        $this->assertEquals(1, $response->json('data.tab_1_ranked_recommendations.0.rank'));

        // Verifikasi bahwa ada kandidat TMS (misal Bintara/Tamtama tidak boleh jadi Kapolsek)
        $ineligibleCount = count($response->json('data.tab_2_ineligible_personnel'));
        $this->assertGreaterThan(0, $ineligibleCount);
    }

    public function test_can_get_recommendation_history_and_detail(): void
    {
        $position = Position::query()->where('code', 'BINTARA_PENYIDIK')->first();
        $this->assertNotNull($position);

        $runResponse = $this->postJson('/api/v1/recommendations/run', [
            'position_id' => $position->id,
            'period_year' => 2026,
        ]);
        $runResponse->assertStatus(201);
        $runId = $runResponse->json('data.run_info.id');

        // Test History endpoint
        $historyResponse = $this->getJson('/api/v1/recommendations/history');
        $historyResponse->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => ['id', 'position_id', 'period_year', 'total_candidates', 'eligible_candidates'],
                ],
            ]);

        // Test Show Detail endpoint
        $detailResponse = $this->getJson("/api/v1/recommendations/{$runId}");
        $detailResponse->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.run_info.id', $runId)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'run_info',
                    'tab_1_ranked_recommendations',
                    'tab_2_ineligible_personnel',
                    'tab_3_mathematical_matrices',
                ],
            ]);
    }
}
