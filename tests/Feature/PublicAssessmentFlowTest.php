<?php

namespace Tests\Feature;

use App\Models\Participant;
use App\Models\Province;
use App\Models\Regency;
use App\Models\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicAssessmentFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\MasterDataSeeder::class);
    }

    public function test_participant_registration_generates_random_assessment_code()
    {
        $province = Province::first();
        $regency = Regency::first();
        $school = School::first();

        $response = $this->postJson('/assessment/register', [
            'name' => 'Budi Santoso',
            'birth_date' => '2005-08-17',
            'category' => 'Pelajar',
            'country_id' => 1,
            'province_id' => $province->id,
            'regency_id' => $regency->id,
            'school_level' => 'SMA',
            'school_class' => 'XI IPA 1',
            'school_id' => $school->id,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'assessment_code', 'participant_code', 'take_url']);

        $code = $response->json('assessment_code');
        $this->assertStringStartsWith('RQI-', $code);

        $participant = Participant::where('assessment_code', $code)->first();
        $this->assertNotNull($participant);
        $this->assertEquals('Budi Santoso', $participant->name);
    }

    public function test_check_score_verification_requires_code_and_birth_date()
    {
        $participant = Participant::create([
            'participant_code' => 'PAR-TEST-001',
            'assessment_code' => 'RQI-TEST99',
            'name' => 'Siti Rahma',
            'birth_date' => '2002-03-15',
            'category' => 'Mahasiswa/i',
            'email' => 'siti@example.com',
            'country_id' => 1,
            'status' => 'active',
        ]);

        // Failed lookup with wrong birth date
        $failResponse = $this->post('/assessment/check-score', [
            'assessment_code' => 'RQI-TEST99',
            'birth_date' => '1999-01-01',
        ]);

        $failResponse->assertSessionHas('error');
    }
}
