<?php

namespace Tests\Feature;

use App\Services\RqiScoringEngine;
use Tests\TestCase;

class RqiScoringEngineTest extends TestCase
{
    protected RqiScoringEngine $engine;

    protected function setUp(): void
    {
        parent::setUp();
        $this->engine = new RqiScoringEngine();
    }

    public function test_reverse_scoring_calculation()
    {
        // Positive item
        $this->assertEquals(5, $this->engine->calculateItemScore(5, 'normal'));
        $this->assertEquals(1, $this->engine->calculateItemScore(1, 'normal'));

        // Negative item (reverse: 6 - raw)
        $this->assertEquals(1, $this->engine->calculateItemScore(5, 'reverse'));
        $this->assertEquals(5, $this->engine->calculateItemScore(1, 'reverse'));
        $this->assertEquals(3, $this->engine->calculateItemScore(3, 'reverse'));
    }

    public function test_rqi_category_classification_thresholds()
    {
        // 65 - 75: Level 5: Enlightened Soul
        $cat1 = $this->engine->getCategoryLevel(70);
        $this->assertEquals('Level 5: Enlightened Soul (Jiwa Terpancar Sempurna)', $cat1['name']);

        // 54 - 64: Level 4: Mindful Youth
        $cat2 = $this->engine->getCategoryLevel(60);
        $this->assertEquals('Level 4: Mindful Youth (Jiwa Tenang & Terjaga)', $cat2['name']);

        // 41 - 53: Level 3: Developing Soul
        $cat3 = $this->engine->getCategoryLevel(45);
        $this->assertEquals('Level 3: Developing Soul (Jiwa Berproses Stabil)', $cat3['name']);

        // 27 - 40: Level 2: Awakening Pilgrim
        $cat4 = $this->engine->getCategoryLevel(35);
        $this->assertEquals('Level 2: Awakening Pilgrim (Jiwa Mulai Tergugah)', $cat4['name']);

        // 15 - 26: Level 1: Spiritual Lowbat
        $cat5 = $this->engine->getCategoryLevel(20);
        $this->assertEquals('Level 1: Spiritual Lowbat (Jiwa Kelelahan)', $cat5['name']);
    }

    public function test_who5_score_and_screening_threshold()
    {
        // Score 20 / 25 = 80% (>= 50%)
        $who1 = $this->engine->calculateWho5Score(20);
        $this->assertEquals(80, $who1['percentage']);
        $this->assertFalse($who1['needs_attention']);

        // Score 10 / 25 = 40% (<= 50%)
        $who2 = $this->engine->calculateWho5Score(10);
        $this->assertEquals(40, $who2['percentage']);
        $this->assertTrue($who2['needs_attention']);
        $this->assertStringContainsString('overthinking', $who2['screening_note']);
    }
}
