<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dimension;
use App\Models\Indicator;
use App\Models\Instrument;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\ScoringRule;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class AdminInstrumentController extends Controller
{
    public function index()
    {
        $instruments = Instrument::withCount(['dimensions', 'questions'])->latest()->paginate(10);
        return view('admin.instruments.index', compact('instruments'));
    }

    public function show(Instrument $instrument)
    {
        $instrument->load([
            'dimensions.indicators',
            'dimensions.questions.options',
            'scoringRules'
        ]);

        return view('admin.instruments.show', compact('instrument'));
    }

    public function storeInstrument(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'unique:instruments,code'],
            'name' => ['required', 'string', 'max:255'],
            'version' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'instructions' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,active,archived'],
        ]);

        $instrument = Instrument::create($validated);

        // Default Scoring Rule
        ScoringRule::create([
            'instrument_id' => $instrument->id,
            'scale_min' => 1,
            'scale_max' => 5,
            'reverse_mapping' => ['1' => 5, '2' => 4, '3' => 3, '4' => 2, '5' => 1],
        ]);

        AuditLogService::log(
            action: 'create_instrument',
            module: 'Assessment',
            recordType: 'Instrument',
            recordId: (string) $instrument->id,
            changes: $validated
        );

        return redirect()->route('admin.instruments.show', $instrument->id)->with('success', 'Instrumen berhasil dibuat.');
    }

    public function storeDimension(Request $request, Instrument $instrument)
    {
        $validated = $request->validate([
            'code' => ['required', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'order' => ['required', 'integer'],
        ]);

        $validated['instrument_id'] = $instrument->id;
        Dimension::create($validated);

        return back()->with('success', 'Dimensi berhasil ditambahkan.');
    }

    public function storeQuestion(Request $request, Instrument $instrument)
    {
        $validated = $request->validate([
            'dimension_id' => ['required', 'exists:dimensions,id'],
            'indicator_id' => ['nullable', 'exists:indicators,id'],
            'question_text' => ['required', 'string'],
            'type' => ['required', 'in:likert,multiple_choice,yes_no'],
            'scoring_direction' => ['required', 'in:normal,reverse'],
            'order' => ['required', 'integer'],
        ]);

        $validated['instrument_id'] = $instrument->id;
        $validated['status'] = 'active';

        $question = Question::create($validated);

        // Auto-create standard Likert options if likert
        if ($validated['type'] === 'likert') {
            $options = [
                ['text' => 'Sangat Tidak Sesuai', 'val' => 1],
                ['text' => 'Tidak Sesuai', 'val' => 2],
                ['text' => 'Netral / Ragu-ragu', 'val' => 3],
                ['text' => 'Sesuai', 'val' => 4],
                ['text' => 'Sangat Sesuai', 'val' => 5],
            ];
            foreach ($options as $idx => $opt) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => $opt['text'],
                    'option_value' => $opt['val'],
                    'order' => $idx + 1,
                ]);
            }
        }

        AuditLogService::log(
            action: 'create_question',
            module: 'Assessment',
            recordType: 'Question',
            recordId: (string) $question->id,
            changes: ['scoring_direction' => $question->scoring_direction]
        );

        return back()->with('success', 'Pertanyaan berhasil ditambahkan.');
    }

    public function updateScoringRule(Request $request, Instrument $instrument)
    {
        $validated = $request->validate([
            'scale_min' => ['required', 'integer'],
            'scale_max' => ['required', 'integer'],
            'reverse_mapping' => ['nullable', 'array'],
        ]);

        $rule = ScoringRule::firstOrCreate(['instrument_id' => $instrument->id]);
        $rule->update($validated);

        AuditLogService::log(
            action: 'update_scoring_rule',
            module: 'Assessment',
            recordType: 'ScoringRule',
            recordId: (string) $rule->id,
            changes: $validated
        );

        return back()->with('success', 'Konfigurasi scoring engine berhasil diperbarui.');
    }
}
