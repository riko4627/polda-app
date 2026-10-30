<?php

namespace Tests\Feature;

use App\Models\Position;
use App\Models\TopsisRun;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhaseFourWebFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_can_browse_recommendations_launch_page(): void
    {
        $response = $this->get('/recommendations');

        $response->assertStatus(200);
        $response->assertSee('Simulasi Rekomendasi Sidang Wanjak');
        $response->assertSee('Pilih Jabatan Target');
        $response->assertSee('KAPOLSEK');
    }

    public function test_can_run_recommendation_and_redirect_to_results_3_tabs(): void
    {
        $position = Position::query()->where('code', 'KAPOLSEK')->first();
        $this->assertNotNull($position);

        $payload = [
            'position_id' => $position->id,
            'period_year' => 2026,
            'notes' => 'Uji Sidang Wanjak Kapolsek 2026',
        ];

        $response = $this->post('/recommendations/run', $payload);

        $run = TopsisRun::query()->where('position_id', $position->id)->latest()->first();
        $this->assertNotNull($run);

        $response->assertRedirect(route('web.recommendations.show', $run->id));

        // Follow Redirect to 3 Tab Result Page
        $detailResponse = $this->get(route('web.recommendations.show', $run->id));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee($position->title);
        $detailResponse->assertSee('Tab 1: Peringkat Rekomendasi');
        $detailResponse->assertSee('Tab 2: Personel TMS');
        $detailResponse->assertSee('Tab 3: Transparansi Matriks Matematis');

        // Verifikasi bahwa kandidat terpilih memiliki status Kuota
        $detailResponse->assertSee('Rekomendasi Utama (Terpilih)');
    }

    public function test_can_browse_recommendation_history_page(): void
    {
        $position = Position::query()->first();
        $this->assertNotNull($position);

        $this->post('/recommendations/run', [
            'position_id' => $position->id,
            'period_year' => 2026,
        ]);

        $response = $this->get('/recommendations/history');

        $response->assertStatus(200);
        $response->assertSee('Riwayat Keputusan Sidang Wanjak');
        $response->assertSee('Buka 3 Tab Hasil');
    }
}
