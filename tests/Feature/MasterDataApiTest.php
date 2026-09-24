<?php

namespace Tests\Feature;

use App\Models\Province;
use App\Models\Regency;
use App\Models\School;
use App\Models\University;
use App\Models\Faculty;
use App\Models\StudyProgram;
use App\Models\Occupation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterDataApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\MasterDataSeeder::class);
    }

    public function test_provinces_api_returns_json_list()
    {
        $response = $this->getJson('/api/master/provinces?q=Jambi');

        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'data' => [['id', 'code', 'name']]]);
    }

    public function test_regencies_api_cascades_by_province()
    {
        $province = Province::where('name', 'Jambi')->first();
        $this->assertNotNull($province);

        $response = $this->getJson("/api/master/regencies?province_id={$province->id}&q=Muaro");

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Muaro Jambi']);
    }

    public function test_schools_api_cascades_by_province_regency_and_level()
    {
        $province = Province::where('name', 'Jambi')->first();
        $regency = Regency::where('name', 'Muaro Jambi')->first();

        $response = $this->getJson("/api/master/schools?province_id={$province->id}&regency_id={$regency->id}&level=SMA&q=SMA");

        $response->assertStatus(200)
            ->assertJsonFragment(['level' => 'SMA']);
    }

    public function test_universities_faculties_and_study_programs_cascading()
    {
        $university = University::where('name', 'like', '%UIN%')->first();
        $this->assertNotNull($university);

        $facResponse = $this->getJson("/api/master/faculties?university_id={$university->id}");
        $facResponse->assertStatus(200);

        $spResponse = $this->getJson("/api/master/study-programs?university_id={$university->id}");
        $spResponse->assertStatus(200);
    }
}
