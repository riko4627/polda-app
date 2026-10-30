<?php

namespace Tests\Feature;

use App\Models\Criterion;
use App\Models\Personnel;
use App\Models\Position;
use App\Models\Rank;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhaseThreeWebFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_can_browse_personnel_list(): void
    {
        $response = $this->get('/personnel');

        $response->assertStatus(200);
        $response->assertSee('Data Personel Polri');
        $response->assertSee('Tambah Personel Baru');
    }

    public function test_can_open_create_personnel_form(): void
    {
        $response = $this->get('/personnel/create');

        $response->assertStatus(200);
        $response->assertSee('Nomor Registrasi Pokok (NRP)');
        $response->assertSee('Simpan Data Personel');
    }

    public function test_can_create_new_personnel_via_web_form(): void
    {
        $rank = Rank::query()->where('code', 'AKP')->first();
        $this->assertNotNull($rank);

        $payload = [
            'nrp' => '86010999',
            'name' => 'Arya Wiguna, S.H.',
            'rank_id' => $rank->id,
            'education_level' => 'S1',
            'education_major' => 'Hukum',
            'has_sespimmen' => 0,
            'current_position' => 'Kasat Binmas',
            'unit_name' => 'Polresta Sleman',
            'function_track' => 'Binmas',
            'phone' => '081234567899',
            'email' => 'arya.wiguna@polri.go.id',
            'status' => 'active',
        ];

        $response = $this->post('/personnel', $payload);

        $this->assertDatabaseHas('personnel', ['nrp' => '86010999']);
        $newPersonnel = Personnel::query()->where('nrp', '86010999')->first();
        $response->assertRedirect(route('web.personnel.show', $newPersonnel->id));
    }

    public function test_can_view_personnel_profile_and_input_scores(): void
    {
        $personnel = Personnel::query()->first();
        $this->assertNotNull($personnel);

        $response = $this->get('/personnel/'.$personnel->id);

        $response->assertStatus(200);
        $response->assertSee($personnel->name);
        $response->assertSee('Nilai Penilaian Kinerja');

        $criteria = Criterion::all();
        $scoresPayload = [
            'period_year' => 2026,
            'scores' => [],
        ];
        foreach ($criteria as $c) {
            $scoresPayload['scores'][$c->id] = 87.5;
        }

        $postScore = $this->post("/personnel/{$personnel->id}/scores", $scoresPayload);
        $postScore->assertRedirect();
        $postScore->assertSessionHas('success');

        $this->assertDatabaseHas('assessment_scores', [
            'personnel_id' => $personnel->id,
            'period_year' => 2026,
            'score' => 87.5,
        ]);
    }

    public function test_can_browse_positions_list(): void
    {
        $response = $this->get('/positions');

        $response->assertStatus(200);
        $response->assertSee('Manajemen Jabatan');
        $response->assertSee('KAPOLSEK');
        $response->assertSee('KABAGDALPERS');
    }

    public function test_can_view_position_detail_and_edit_rules(): void
    {
        $position = Position::query()->where('code', 'KAPOLSEK')->first();
        $this->assertNotNull($position);

        $response = $this->get('/positions/'.$position->id);
        $response->assertStatus(200);
        $response->assertSee($position->title);
        $response->assertSee('Aturan Syarat Mutlak');

        // Test Edit View
        $editView = $this->get("/positions/{$position->id}/edit");
        $editView->assertStatus(200);
        $editView->assertSee('Simpan Aturan Syarat Mutlak');

        // Test Update Rules
        $updateResponse = $this->put("/positions/{$position->id}", [
            'title' => 'Kapolsek Urban Wilayah',
            'unit_name' => 'Polsek Jajaran Tipe Urban',
            'quota' => 4,
            'min_rank_id' => $position->rule?->min_rank_id,
            'min_education_level' => 'S1',
        ]);

        $updateResponse->assertRedirect(route('web.positions.show', $position->id));
        $this->assertDatabaseHas('positions', [
            'id' => $position->id,
            'title' => 'Kapolsek Urban Wilayah',
            'quota' => 4,
        ]);
        $this->assertDatabaseHas('position_rules', [
            'position_id' => $position->id,
            'min_education_level' => 'S1',
        ]);
    }

    public function test_can_update_position_weights_with_validation_100_percent(): void
    {
        $position = Position::query()->where('code', 'KAPOLRES')->first();
        $this->assertNotNull($position);

        $response = $this->get("/positions/{$position->id}/weights");
        $response->assertStatus(200);
        $response->assertSee('Pengaturan Bobot Kriteria TOPSIS');

        $criteria = Criterion::all();
        $this->assertCount(6, $criteria);

        // Test Invalid Weights (sum = 0.5 != 1.0) -> must fail
        $invalidPayload = [
            'weights' => [
                $criteria[0]->id => 0.25,
                $criteria[1]->id => 0.25,
            ],
        ];

        $failResponse = $this->post("/positions/{$position->id}/weights", $invalidPayload);
        $failResponse->assertRedirect();
        $failResponse->assertSessionHas('error');

        // Test Valid Weights (sum = 1.0) -> must succeed
        $validPayload = [
            'weights' => [
                $criteria[0]->id => 0.25,
                $criteria[1]->id => 0.20,
                $criteria[2]->id => 0.15,
                $criteria[3]->id => 0.15,
                $criteria[4]->id => 0.15,
                $criteria[5]->id => 0.10,
            ],
        ];

        $successResponse = $this->post("/positions/{$position->id}/weights", $validPayload);
        $successResponse->assertRedirect(route('web.positions.show', $position->id));
        $successResponse->assertSessionHas('success');
    }

    public function test_can_browse_criteria_list(): void
    {
        $response = $this->get('/criteria');

        $response->assertStatus(200);
        $response->assertSee('Master Kriteria Penilaian');
        $response->assertSee('Nilai SMK');
        $response->assertSee('Nilai Psikologi');
    }
}
