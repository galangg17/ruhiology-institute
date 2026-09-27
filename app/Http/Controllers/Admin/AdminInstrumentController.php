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
        $instruments = Instrument::withCount(['dimensions', 'questions'])->latest()->paginate(12);
        return view('admin.instruments.index', compact('instruments'));
    }

    public function show(Instrument $instrument)
    {
        $instrument->load([
            'dimensions.indicators',
            'dimensions.questions.options',
            'questions.dimension',
            'questions.options',
            'scoringRules'
        ]);

        $dimensions = $instrument->dimensions;

        return view('admin.instruments.show', compact('instrument', 'dimensions'));
    }

    public function storeInstrument(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:instruments,code'],
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

        // Create 5 default RQI Dimensions if none exist
        $defaultDimensions = [
            ['code' => 'RQI-D1', 'name' => 'Kesadaran Ruhaniah / Spiritual Awareness', 'order' => 1],
            ['code' => 'RQI-D2', 'name' => 'Ketenangan Batin & Regulasi Diri', 'order' => 2],
            ['code' => 'RQI-D3', 'name' => 'Moralitas & Integritas Karakter', 'order' => 3],
            ['code' => 'RQI-D4', 'name' => 'Empati & Hubungan Transendental', 'order' => 4],
            ['code' => 'RQI-D5', 'name' => 'Orientasi Makna & Tujuan Hidup', 'order' => 5],
        ];

        foreach ($defaultDimensions as $dim) {
            Dimension::create([
                'instrument_id' => $instrument->id,
                'code' => $dim['code'],
                'name' => $dim['name'],
                'order' => $dim['order'],
            ]);
        }

        AuditLogService::log(
            action: 'create_instrument',
            module: 'Assessment',
            recordType: 'Instrument',
            recordId: (string) $instrument->id,
            changes: $validated
        );

        return redirect()->route('admin.instruments.show', $instrument->id)->with('success', 'Paket Soal Baru "' . $instrument->name . '" berhasil dibuat.');
    }

    public function updateInstrument(Request $request, Instrument $instrument)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:instruments,code,' . $instrument->id],
            'name' => ['required', 'string', 'max:255'],
            'version' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'instructions' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,active,archived'],
        ]);

        $instrument->update($validated);

        AuditLogService::log(
            action: 'update_instrument',
            module: 'Assessment',
            recordType: 'Instrument',
            recordId: (string) $instrument->id,
            changes: $validated
        );

        return back()->with('success', 'Paket Soal "' . $instrument->name . '" berhasil diperbarui.');
    }

    public function destroyInstrument(Instrument $instrument)
    {
        $name = $instrument->name;
        $instrument->delete();

        AuditLogService::log(
            action: 'delete_instrument',
            module: 'Assessment',
            recordType: 'Instrument',
            recordId: (string) $instrument->id,
            changes: ['name' => $name]
        );

        return redirect()->route('admin.instruments.index')->with('success', 'Paket Soal "' . $name . '" berhasil dihapus.');
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

    public function destroyDimension(Instrument $instrument, Dimension $dimension)
    {
        $dimension->delete();
        return back()->with('success', 'Dimensi berhasil dihapus.');
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

        return back()->with('success', 'Pertanyaan baru berhasil ditambahkan ke Bank Soal.');
    }

    public function updateQuestion(Request $request, Instrument $instrument, Question $question)
    {
        $validated = $request->validate([
            'dimension_id' => ['required', 'exists:dimensions,id'],
            'question_text' => ['required', 'string'],
            'type' => ['required', 'in:likert,multiple_choice,yes_no'],
            'scoring_direction' => ['required', 'in:normal,reverse'],
            'order' => ['required', 'integer'],
        ]);

        $question->update($validated);

        return back()->with('success', 'Pertanyaan berhasil diperbarui.');
    }

    public function destroyQuestion(Instrument $instrument, Question $question)
    {
        $question->delete();
        return back()->with('success', 'Pertanyaan berhasil dihapus dari Bank Soal.');
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

    public function toggleAccessType(Instrument $instrument)
    {
        $newAccess = ($instrument->access_type === 'public') ? 'event_only' : 'public';
        $instrument->update(['access_type' => $newAccess]);

        $label = ($newAccess === 'public') ? '🌐 Akses Publik (ON)' : '🔒 Khusus Event';
        return back()->with('success', 'Tipe akses paket "' . $instrument->name . '" berhasil diubah menjadi ' . $label . '.');
    }

    public function downloadTemplate()
    {
        $filename = "template_import_paket_soal.csv";
        $headers = [
            "Content-Type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=\"$filename\"",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['Kode Dimensi', 'Nama Dimensi', 'Teks Pertanyaan', 'Skoring']);
            fputcsv($handle, ['DIM-1', 'Kesadaran Diri Hakiki', 'Saya menyadari bahwa diri saya bukan sekadar fisik melainkan ruh yang sedang berproses.', 'normal']);
            fputcsv($handle, ['DIM-1', 'Kesadaran Diri Hakiki', 'Saya sering merasa cemas dan hampa meskipun meraih pencapaian materi.', 'reverse']);
            fputcsv($handle, ['DIM-WHO5', 'Indeks Kesejahteraan Mental (WHO-5)', 'Saya merasa bersemangat, ceria, dan termotivasi dalam menjalani hari-hari.', 'normal']);

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportCsv(Instrument $instrument)
    {
        $instrument->load('questions.dimension');
        $filename = "paket_soal_" . \Illuminate\Support\Str::slug($instrument->code) . ".csv";

        $headers = [
            "Content-Type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=\"$filename\"",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($instrument) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['Kode Dimensi', 'Nama Dimensi', 'Teks Pertanyaan', 'Skoring']);

            foreach ($instrument->questions as $q) {
                fputcsv($handle, [
                    $q->dimension->code ?? 'DIM-1',
                    $q->dimension->name ?? 'Dimensi Utama',
                    $q->question_text,
                    $q->scoring_direction ?? 'normal'
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importCsv(Request $request, ?Instrument $instrument = null)
    {
        $request->validate([
            'file' => ['required', 'file', 'max:5120'],
        ]);

        if (!$instrument && $request->has('instrument_id')) {
            $instrument = Instrument::find($request->input('instrument_id'));
        }

        if (!$instrument) {
            $instrument = Instrument::create([
                'code' => 'RQI-IMP-' . strtoupper(\Illuminate\Support\Str::random(4)),
                'name' => 'Hasil Import Paket Soal Excel (' . date('d M Y H:i') . ')',
                'description' => 'Paket Soal yang diimpor dari file Excel / CSV.',
                'version' => '1.0',
                'instructions' => 'Pilih frekuensi yang paling menggambarkan kondisi dan perasaan Anda yang sebenarnya.',
                'status' => 'active',
                'access_type' => 'event_only'
            ]);
            ScoringRule::create([
                'instrument_id' => $instrument->id,
                'scale_min' => 1,
                'scale_max' => 5,
                'reverse_mapping' => ['1' => 5, '2' => 4, '3' => 3, '4' => 2, '5' => 1],
            ]);
        }

        $file = $request->file('file');
        $path = $file->getRealPath();
        
        $handle = fopen($path, 'r');
        if (!$handle) {
            return back()->with('error', 'Gagal membaca file Excel/CSV.');
        }

        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        $header = fgetcsv($handle, 2000, ',');
        $delimiter = ',';
        if ($header && count($header) == 1 && str_contains($header[0], ';')) {
            rewind($handle);
            if ($bom === "\xEF\xBB\xBF") fread($handle, 3);
            $header = fgetcsv($handle, 2000, ';');
            $delimiter = ';';
        }

        $importedCount = 0;
        $order = Question::where('instrument_id', $instrument->id)->max('order') ?? 0;

        while (($data = fgetcsv($handle, 2000, $delimiter)) !== FALSE) {
            if (empty($data) || (count($data) == 1 && trim($data[0]) === '')) continue;

            $dimCode = trim($data[0] ?? 'DIM-1');
            $dimName = trim($data[1] ?? '');
            $questionText = trim($data[2] ?? '');
            $scoring = strtolower(trim($data[3] ?? 'normal'));

            if (empty($questionText) || str_contains(strtolower($dimCode), 'kode dimensi') || str_contains(strtolower($questionText), 'teks pertanyaan')) {
                if (empty($questionText) && !empty($dimName) && !str_contains(strtolower($dimName), 'nama dimensi')) {
                    $questionText = $dimName;
                    $dimName = 'Dimensi ' . $dimCode;
                } else {
                    continue;
                }
            }

            if (empty($dimName)) {
                $dimName = 'Dimensi ' . $dimCode;
            }

            $dimension = Dimension::firstOrCreate(
                ['instrument_id' => $instrument->id, 'code' => $dimCode],
                ['name' => $dimName, 'order' => Dimension::where('instrument_id', $instrument->id)->count() + 1]
            );

            $scoringDirection = str_contains($scoring, 'reverse') ? 'reverse' : 'normal';
            $order++;

            $question = Question::create([
                'instrument_id' => $instrument->id,
                'dimension_id' => $dimension->id,
                'question_text' => $questionText,
                'type' => 'likert',
                'scoring_direction' => $scoringDirection,
                'order' => $order,
                'status' => 'active',
            ]);

            $isWho5 = str_contains(strtolower($dimCode), 'who') || str_contains(strtolower($dimName), 'who');
            $options = $isWho5 ? [
                ['text' => 'Tidak Pernah', 'val' => 1],
                ['text' => 'Jarang', 'val' => 2],
                ['text' => 'Kadang-kadang', 'val' => 3],
                ['text' => 'Sebagian Besar Waktu', 'val' => 4],
                ['text' => 'Sepanjang Waktu', 'val' => 5],
            ] : [
                ['text' => 'Sangat Tidak Sesuai', 'val' => 1],
                ['text' => 'Tidak Sesuai', 'val' => 2],
                ['text' => 'Netral / Ragu-ragu', 'val' => 3],
                ['text' => 'Sesuai', 'val' => 4],
                ['text' => 'Sangat Sesuai', 'val' => 5],
            ];

            foreach ($options as $optIdx => $opt) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => $opt['text'],
                    'option_value' => $opt['val'],
                    'order' => $optIdx + 1,
                ]);
            }

            $importedCount++;
        }

        fclose($handle);

        return back()->with('success', "Berhasil mengimpor {$importedCount} pertanyaan dari file Excel ke Paket Soal \"{$instrument->name}\".");
    }
}
