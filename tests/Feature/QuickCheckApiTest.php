<?php

namespace Tests\Feature;

use App\Models\AssessmentPeriod;
use App\Models\Participant;
use App\Models\Province;
use App\Models\Regency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuickCheckApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\MasterDataSeeder::class);
    }

    public function test_quick_check_api_returns_found_participant_and_posttest_status()
    {
        $province = Province::first();
        $regency = Regency::first();

        $participant = Participant::create([
            'participant_code' => 'PAR-QUICK-01',
            'assessment_code' => 'RQI-QUICK-01',
            'name' => 'Ahmad Syahputra',
            'birth_date' => '2000-01-01',
            'category' => 'Mahasiswa/i',
            'email' => 'ahmad@example.com',
            'country_id' => 1,
            'province_id' => $province->id,
            'regency_id' => $regency->id,
            'status' => 'active',
        ]);

        $response = $this->postJson('/api/assessment/quick-check', [
            'code' => 'RQI-QUICK-01'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'found' => true,
                'participant' => [
                    'name' => 'Ahmad Syahputra',
                    'participant_code' => 'PAR-QUICK-01',
                    'assessment_code' => 'RQI-QUICK-01',
                ]
            ]);
    }

    public function test_quick_check_api_returns_not_found_for_invalid_code()
    {
        $response = $this->postJson('/api/assessment/quick-check', [
            'code' => 'INVALID-CODE-999'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'found' => false
            ]);
    }
}
