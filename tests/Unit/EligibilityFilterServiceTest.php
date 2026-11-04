<?php

namespace Tests\Unit;

use App\Models\Personnel;
use App\Models\Position;
use App\Services\EligibilityFilterService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EligibilityFilterServiceTest extends TestCase
{
    use RefreshDatabase;

    protected EligibilityFilterService $filterService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->filterService = new EligibilityFilterService;
    }

    public function test_kabagdalpers_eligibility_accepts_akbp_sespimmen_with_ex_kapolres(): void
    {
        $position = Position::query()->with('rule.requiredRank')->where('code', 'KABAGDALPERS')->first();
        $this->assertNotNull($position);

        // Budi Santoso: AKBP, Sespimmen = true, ex-Kapolres Sleman
        $candidateEligible = Personnel::query()
            ->with(['rank', 'certifications', 'careerHistories'])
            ->where('nrp', '78010123')
            ->first();
        $this->assertNotNull($candidateEligible);

        $result = $this->filterService->evaluate($candidateEligible, $position->rule);
        $this->assertTrue($result->isEligible);
        $this->assertEmpty($result->reasons);
    }

    public function test_kabagdalpers_eligibility_rejects_akbp_without_ex_kapolres(): void
    {
        $position = Position::query()->with('rule.requiredRank')->where('code', 'KABAGDALPERS')->first();
        $this->assertNotNull($position);

        // Hendra Gunawan: AKBP, Sespimmen = true, tapi belum pernah Kapolres
        $candidateIneligible = Personnel::query()
            ->with(['rank', 'certifications', 'careerHistories'])
            ->where('nrp', '79040456')
            ->first();
        $this->assertNotNull($candidateIneligible);

        $result = $this->filterService->evaluate($candidateIneligible, $position->rule);
        $this->assertFalse($result->isEligible);
        $this->assertStringContainsString('pernah menduduki jabatan \'Kapolres\'', implode(' ', $result->reasons));
    }

    public function test_kapolres_eligibility_rejects_akbp_without_sespimmen(): void
    {
        $position = Position::query()->with('rule.requiredRank')->where('code', 'KAPOLRES')->first();
        $this->assertNotNull($position);

        // Agus Wijaya: AKBP, tapi has_sespimmen = false
        $candidateNoSespimmen = Personnel::query()
            ->with(['rank', 'certifications', 'careerHistories'])
            ->where('nrp', '80050789')
            ->first();
        $this->assertNotNull($candidateNoSespimmen);

        $result = $this->filterService->evaluate($candidateNoSespimmen, $position->rule);
        $this->assertFalse($result->isEligible);
        $this->assertStringContainsString('Pendidikan Pengembangan Sespimmen', implode(' ', $result->reasons));
    }

    public function test_kanit_reskrim_eligibility_rejects_pama_below_iptu_and_without_s1(): void
    {
        $position = Position::query()->with(['rule.minRank'])->where('code', 'KANIT_RESKRIM')->first();
        $this->assertNotNull($position);
        $this->assertEquals('Kanit Reskrim Polres', $position->title);

        // Farhan Ramadhan: IPDA (dibawah IPTU), SMA (dibawah S1), belum sertifikasi penyidik/reskrim
        $candidateIpda = Personnel::query()
            ->with(['rank', 'certifications', 'careerHistories'])
            ->where('nrp', '95090444')
            ->first();
        $this->assertNotNull($candidateIpda);

        $result = $this->filterService->evaluate($candidateIpda, $position->rule);
        $this->assertFalse($result->isEligible);
        $this->assertCount(3, $result->reasons); // Pangkat kurang, Pendidikan kurang, Belum sertifikasi
    }

    public function test_kanit_reskrim_polres_eligibility_accepts_iptu_s1_with_dikjur_reskrim(): void
    {
        $position = Position::query()->with(['rule.minRank'])->where('code', 'KANIT_RESKRIM')->first();
        $this->assertNotNull($position);

        // Rian Anggoro: IPTU, S1, sertifikasi & Dikjur Reserse Kriminal
        $candidateIptu = Personnel::query()
            ->with(['rank', 'certifications', 'careerHistories'])
            ->where('nrp', '91080333')
            ->first();
        $this->assertNotNull($candidateIptu);

        $result = $this->filterService->evaluate($candidateIptu, $position->rule);
        $this->assertTrue($result->isEligible);
        $this->assertEmpty($result->reasons);
    }

    public function test_wadir_reskrim_eligibility_accepts_akbp_sespimmen_with_dikjur_reskrim(): void
    {
        $position = Position::query()->with(['rule.requiredRank'])->where('code', 'WADIR_RESKRIM')->first();
        $this->assertNotNull($position);
        $this->assertEquals('Reskrim', $position->rule->required_certification_keyword);

        // Budi Santoso: AKBP, Sespimmen = true, track Reskrim, sertifikasi Dikjur Reskrim
        $candidateEligible = Personnel::query()
            ->with(['rank', 'certifications', 'careerHistories'])
            ->where('nrp', '78010123')
            ->first();
        $this->assertNotNull($candidateEligible);

        $result = $this->filterService->evaluate($candidateEligible, $position->rule);
        $this->assertTrue($result->isEligible);
        $this->assertEmpty($result->reasons);
    }

    public function test_wadir_reskrim_eligibility_rejects_candidate_without_dikjur_reskrim(): void
    {
        $position = Position::query()->with(['rule.requiredRank'])->where('code', 'WADIR_RESKRIM')->first();
        $this->assertNotNull($position);

        // Hendra Gunawan: AKBP, Sespimmen = true, tapi fungsi Lantas dan belum Dikjur Reskrim
        $candidateNoReskrim = Personnel::query()
            ->with(['rank', 'certifications', 'careerHistories'])
            ->where('nrp', '79040456')
            ->first();
        $this->assertNotNull($candidateNoReskrim);

        $result = $this->filterService->evaluate($candidateNoReskrim, $position->rule);
        $this->assertFalse($result->isEligible);
        $this->assertStringContainsString('Reskrim', implode(' ', $result->reasons));
    }

    public function test_bintara_penyidik_eligibility(): void
    {
        $position = Position::query()->with('rule')->where('code', 'BINTARA_PENYIDIK')->first();
        $this->assertNotNull($position);

        // Bambang Sutrisno: Aiptu (Bintara), sertifikasi Dikjur Penyidik Pembantu
        $candidatePenyidik = Personnel::query()
            ->with(['rank', 'certifications', 'careerHistories'])
            ->where('nrp', '77100555')
            ->first();
        $this->assertNotNull($candidatePenyidik);

        $result = $this->filterService->evaluate($candidatePenyidik, $position->rule);
        $this->assertTrue($result->isEligible);

        // Bripda Rizky Pratama: Bintara, tapi sertifikasi Bhabinkamtibmas (bukan Penyidik)
        $candidateBhabin = Personnel::query()
            ->with(['rank', 'certifications', 'careerHistories'])
            ->where('nrp', '01120777')
            ->first();
        $this->assertNotNull($candidateBhabin);

        $resultBhabin = $this->filterService->evaluate($candidateBhabin, $position->rule);
        $this->assertFalse($resultBhabin->isEligible);
        $this->assertStringContainsString('Penyidik', implode(' ', $resultBhabin->reasons));
    }
}
