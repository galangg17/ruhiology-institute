<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomForm;
use App\Models\CustomFormField;
use App\Models\CustomFormResponse;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminCustomFormController extends Controller
{
    public function index(Request $request)
    {
        $query = CustomForm::withCount(['fields', 'responses'])
            ->with(['creator']);

        if ($request->filled('q')) {
            $q = trim($request->input('q'));
            $query->where(function ($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                  ->orWhere('description', 'like', "%{$q}%")
                  ->orWhere('slug', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $forms = $query->latest()->paginate(12)->withQueryString();

        $stats = [
            'total' => CustomForm::count(),
            'published' => CustomForm::where('status', 'published')->count(),
            'closed' => CustomForm::whereIn('status', ['closed', 'draft'])->count(),
            'total_responses' => CustomFormResponse::count(),
            'total_forms' => CustomForm::count(),
            'published_forms' => CustomForm::where('status', 'published')->count(),
        ];

        return view('admin.forms.index', compact('forms', 'stats'));
    }

    public function create()
    {
        return view('admin.forms.create');
    }

    public function store(Request $request)
    {
        // Auto-clean any blank or unused field cards before validation
        if ($request->has('fields') && is_array($request->input('fields'))) {
            $cleanedFields = array_values(array_filter($request->input('fields'), function ($f) {
                return !empty(trim($f['label'] ?? ''));
            }));
            $request->merge(['fields' => $cleanedFields]);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:custom_forms,slug'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published,closed'],
            'access_code' => ['nullable', 'string', 'max:50'],
            'closes_at' => ['nullable', 'date'],
            'success_message' => ['nullable', 'string', 'max:1000'],
            'header_image' => ['nullable', 'string'],
            'header_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'fields' => ['required', 'array', 'min:1'],
            'fields.*.label' => ['required', 'string', 'max:255'],
            'fields.*.field_type' => ['required', 'in:text,textarea,number,radio,checkbox,dropdown,date,file'],
            'fields.*.description' => ['nullable', 'string'],
            'fields.*.placeholder' => ['nullable', 'string', 'max:255'],
            'fields.*.is_required' => ['nullable'],
            'fields.*.options' => ['nullable'],

            // Certificate Configurations
            'has_certificate' => ['nullable'],
            'certificate_status' => ['nullable', 'in:locked,released'],
            'certificate_event_name' => ['nullable', 'string', 'max:255'],
            'certificate_event_date' => ['nullable', 'string', 'max:255'],
            'certificate_event_location' => ['nullable', 'string', 'max:255'],
            'certificate_total_jp' => ['nullable', 'string', 'max:100'],
            'certificate_number_pattern' => ['nullable', 'string', 'max:255'],
            'certificate_signer1_name' => ['nullable', 'string', 'max:255'],
            'certificate_signer1_role' => ['nullable', 'string', 'max:255'],
            'certificate_signer1_signature' => ['nullable', 'string'],
            'signer1_sig_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'certificate_signer2_name' => ['nullable', 'string', 'max:255'],
            'certificate_signer2_role' => ['nullable', 'string', 'max:255'],
            'certificate_signer2_signature' => ['nullable', 'string'],
            'signer2_sig_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'certificate_partner_logo' => ['nullable', 'string'],
            'partner_logo_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'certificate_curriculum' => ['nullable'],
            'certificate_name_field_id' => ['nullable', 'integer'],
            'certificate_phone_field_id' => ['nullable', 'integer'],
            'certificate_inst_field_id' => ['nullable', 'integer'],
        ], [
            'title.required' => 'Judul formulir wajib diisi.',
            'fields.required' => 'Formulir harus memiliki minimal 1 pertanyaan.',
            'fields.min' => 'Formulir harus memiliki minimal 1 pertanyaan dengan judul yang jelas.',
            'fields.*.label.required' => 'Pertanyaan nomor :position wajib memiliki judul/label.',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = CustomForm::generateUniqueSlug($validated['title']);
        } else {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        // Header image
        if ($request->hasFile('header_file')) {
            $file = $request->file('header_file');
            $filename = 'form_hdr_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $path = public_path('images/forms');
            if (!file_exists($path)) mkdir($path, 0755, true);
            $file->move($path, $filename);
            $validated['header_image'] = asset('images/forms/' . $filename);
        }

        // Signer 1 file
        if ($request->hasFile('signer1_sig_file')) {
            $file = $request->file('signer1_sig_file');
            $fn = 'sig1_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $p = public_path('images/forms/signatures');
            if (!file_exists($p)) mkdir($p, 0755, true);
            $file->move($p, $fn);
            $validated['certificate_signer1_signature'] = asset('images/forms/signatures/' . $fn);
        }

        // Signer 2 file
        if ($request->hasFile('signer2_sig_file')) {
            $file = $request->file('signer2_sig_file');
            $fn = 'sig2_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $p = public_path('images/forms/signatures');
            if (!file_exists($p)) mkdir($p, 0755, true);
            $file->move($p, $fn);
            $validated['certificate_signer2_signature'] = asset('images/forms/signatures/' . $fn);
        }

        // Partner Logo file
        if ($request->hasFile('partner_logo_file')) {
            $file = $request->file('partner_logo_file');
            $fn = 'logo_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $p = public_path('images/forms/logos');
            if (!file_exists($p)) mkdir($p, 0755, true);
            $file->move($p, $fn);
            $validated['certificate_partner_logo'] = asset('images/forms/logos/' . $fn);
        }

        // Process Curriculum
        $curriculumInput = $request->input('certificate_curriculum', []);
        if (is_string($curriculumInput)) {
            $curriculumInput = json_decode($curriculumInput, true) ?? [];
        }
        $cleanCurriculum = [];
        if (is_array($curriculumInput)) {
            foreach ($curriculumInput as $c) {
                if (!empty(trim($c['title'] ?? ''))) {
                    $cleanCurriculum[] = [
                        'title' => trim($c['title']),
                        'jp' => trim((string)($c['jp'] ?? '4 JP')),
                    ];
                }
            }
        }
        $validated['certificate_curriculum'] = json_encode($cleanCurriculum);
        $validated['has_certificate'] = $request->boolean('has_certificate');
        $validated['certificate_status'] = $request->input('certificate_status', 'locked');

        DB::transaction(function () use ($validated, $request) {
            $form = CustomForm::create([
                'title' => trim($validated['title']),
                'slug' => $validated['slug'],
                'description' => $validated['description'] ?? null,
                'header_image' => $validated['header_image'] ?? null,
                'status' => $validated['status'],
                'access_code' => !empty($validated['access_code']) ? strtoupper(trim($validated['access_code'])) : null,
                'closes_at' => $validated['closes_at'] ?? null,
                'success_message' => $validated['success_message'] ?? 'Terima kasih, data formulir Anda telah berhasil dikirimkan.',
                'created_by' => Auth::id(),

                'has_certificate' => $validated['has_certificate'],
                'certificate_status' => $validated['certificate_status'],
                'certificate_event_name' => $validated['certificate_event_name'] ?? null,
                'certificate_event_date' => $validated['certificate_event_date'] ?? null,
                'certificate_event_location' => $validated['certificate_event_location'] ?? null,
                'certificate_total_jp' => $validated['certificate_total_jp'] ?? null,
                'certificate_number_pattern' => $validated['certificate_number_pattern'] ?? null,
                'certificate_signer1_name' => $validated['certificate_signer1_name'] ?? 'Prof. Dr. Iskandar Nazari, M.Pd',
                'certificate_signer1_role' => $validated['certificate_signer1_role'] ?? 'Direktur Ruhiology Institute',
                'certificate_signer1_signature' => $validated['certificate_signer1_signature'] ?? null,
                'certificate_signer2_name' => $validated['certificate_signer2_name'] ?? null,
                'certificate_signer2_role' => $validated['certificate_signer2_role'] ?? null,
                'certificate_signer2_signature' => $validated['certificate_signer2_signature'] ?? null,
                'certificate_partner_logo' => $validated['certificate_partner_logo'] ?? null,
                'certificate_curriculum' => $validated['certificate_curriculum'],
                'certificate_name_field_id' => $validated['certificate_name_field_id'] ?? null,
                'certificate_phone_field_id' => $validated['certificate_phone_field_id'] ?? null,
                'certificate_inst_field_id' => $validated['certificate_inst_field_id'] ?? null,
            ]);

            $order = 1;
            foreach ($request->input('fields', []) as $f) {
                if (empty($f['label'])) continue;

                $rawOptions = $f['options'] ?? null;
                $optionsArray = null;
                if (!empty($rawOptions)) {
                    if (is_array($rawOptions)) {
                        $optionsArray = array_values(array_filter(array_map('trim', $rawOptions)));
                    } else {
                        $optionsArray = array_values(array_filter(array_map('trim', preg_split('/[\r\n,]+/', (string) $rawOptions))));
                    }
                }

                CustomFormField::create([
                    'custom_form_id' => $form->id,
                    'label' => trim($f['label']),
                    'description' => !empty($f['description']) ? trim($f['description']) : null,
                    'field_type' => $f['field_type'] ?? 'text',
                    'options' => $optionsArray,
                    'is_required' => !empty($f['is_required']) && ($f['is_required'] === true || $f['is_required'] === '1' || $f['is_required'] === 1),
                    'placeholder' => !empty($f['placeholder']) ? trim($f['placeholder']) : null,
                    'order' => $order++,
                ]);
            }

            AuditLogService::log(
                action: 'create_custom_form',
                module: 'Forms',
                recordType: 'CustomForm',
                recordId: (string) $form->id,
                changes: [
                    'title' => $form->title,
                    'slug' => $form->slug,
                    'has_certificate' => $form->has_certificate,
                ]
            );
        });

        return redirect()->route('admin.forms.index')->with('success', 'Formulir kuisioner baru berhasil dibuat dan siap dibagikan!');
    }

    public function edit(CustomForm $form)
    {
        $form->load('fields');

        $fieldsData = $form->fields->map(function ($f) {
            return [
                'id' => $f->id,
                'label' => $f->label,
                'field_type' => $f->field_type,
                'placeholder' => $f->placeholder ?? '',
                'is_required' => (bool) $f->is_required,
                'options' => $f->options ?? [],
            ];
        })->values()->toArray();

        $curriculumData = $form->parsed_curriculum;

        return view('admin.forms.edit', compact('form', 'fieldsData', 'curriculumData'));
    }

    public function update(Request $request, CustomForm $form)
    {
        // Auto-clean any blank or unused field cards before validation
        if ($request->has('fields') && is_array($request->input('fields'))) {
            $cleanedFields = array_values(array_filter($request->input('fields'), function ($f) {
                return !empty(trim($f['label'] ?? ''));
            }));
            $request->merge(['fields' => $cleanedFields]);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:custom_forms,slug,' . $form->id],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published,closed'],
            'access_code' => ['nullable', 'string', 'max:50'],
            'closes_at' => ['nullable', 'date'],
            'success_message' => ['nullable', 'string', 'max:1000'],
            'header_image' => ['nullable', 'string'],
            'header_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'fields' => ['required', 'array', 'min:1'],
            'fields.*.label' => ['required', 'string', 'max:255'],
            'fields.*.field_type' => ['required', 'in:text,textarea,number,radio,checkbox,dropdown,date,file'],
            'fields.*.description' => ['nullable', 'string'],
            'fields.*.placeholder' => ['nullable', 'string', 'max:255'],
            'fields.*.is_required' => ['nullable'],
            'fields.*.options' => ['nullable'],

            // Certificate Configurations
            'has_certificate' => ['nullable'],
            'certificate_status' => ['nullable', 'in:locked,released'],
            'certificate_event_name' => ['nullable', 'string', 'max:255'],
            'certificate_event_date' => ['nullable', 'string', 'max:255'],
            'certificate_event_location' => ['nullable', 'string', 'max:255'],
            'certificate_total_jp' => ['nullable', 'string', 'max:100'],
            'certificate_number_pattern' => ['nullable', 'string', 'max:255'],
            'certificate_signer1_name' => ['nullable', 'string', 'max:255'],
            'certificate_signer1_role' => ['nullable', 'string', 'max:255'],
            'certificate_signer1_signature' => ['nullable', 'string'],
            'signer1_sig_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'certificate_signer2_name' => ['nullable', 'string', 'max:255'],
            'certificate_signer2_role' => ['nullable', 'string', 'max:255'],
            'certificate_signer2_signature' => ['nullable', 'string'],
            'signer2_sig_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'certificate_partner_logo' => ['nullable', 'string'],
            'partner_logo_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'certificate_curriculum' => ['nullable'],
            'certificate_name_field_id' => ['nullable', 'integer'],
            'certificate_phone_field_id' => ['nullable', 'integer'],
            'certificate_inst_field_id' => ['nullable', 'integer'],
        ], [
            'title.required' => 'Judul formulir wajib diisi.',
            'fields.required' => 'Formulir harus memiliki minimal 1 pertanyaan.',
            'fields.min' => 'Formulir harus memiliki minimal 1 pertanyaan dengan judul yang jelas.',
            'fields.*.label.required' => 'Pertanyaan nomor :position wajib memiliki judul/label.',
        ]);

        $validated['slug'] = Str::slug($validated['slug']);

        if ($request->hasFile('header_file')) {
            $file = $request->file('header_file');
            $filename = 'form_hdr_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $path = public_path('images/forms');
            if (!file_exists($path)) mkdir($path, 0755, true);
            $file->move($path, $filename);
            $validated['header_image'] = asset('images/forms/' . $filename);
        }

        // Signer 1 file
        if ($request->hasFile('signer1_sig_file')) {
            $file = $request->file('signer1_sig_file');
            $fn = 'sig1_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $p = public_path('images/forms/signatures');
            if (!file_exists($p)) mkdir($p, 0755, true);
            $file->move($p, $fn);
            $validated['certificate_signer1_signature'] = asset('images/forms/signatures/' . $fn);
        }

        // Signer 2 file
        if ($request->hasFile('signer2_sig_file')) {
            $file = $request->file('signer2_sig_file');
            $fn = 'sig2_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $p = public_path('images/forms/signatures');
            if (!file_exists($p)) mkdir($p, 0755, true);
            $file->move($p, $fn);
            $validated['certificate_signer2_signature'] = asset('images/forms/signatures/' . $fn);
        }

        // Partner Logo file
        if ($request->hasFile('partner_logo_file')) {
            $file = $request->file('partner_logo_file');
            $fn = 'logo_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $p = public_path('images/forms/logos');
            if (!file_exists($p)) mkdir($p, 0755, true);
            $file->move($p, $fn);
            $validated['certificate_partner_logo'] = asset('images/forms/logos/' . $fn);
        }

        // Process Curriculum
        $curriculumInput = $request->input('certificate_curriculum', []);
        if (is_string($curriculumInput)) {
            $curriculumInput = json_decode($curriculumInput, true) ?? [];
        }
        $cleanCurriculum = [];
        if (is_array($curriculumInput)) {
            foreach ($curriculumInput as $c) {
                if (!empty(trim($c['title'] ?? ''))) {
                    $cleanCurriculum[] = [
                        'title' => trim($c['title']),
                        'jp' => trim((string)($c['jp'] ?? '4 JP')),
                    ];
                }
            }
        }
        $validated['certificate_curriculum'] = json_encode($cleanCurriculum);
        $validated['has_certificate'] = $request->boolean('has_certificate');
        $validated['certificate_status'] = $request->input('certificate_status', $form->certificate_status ?? 'locked');

        DB::transaction(function () use ($form, $validated, $request) {
            $form->update([
                'title' => trim($validated['title']),
                'slug' => $validated['slug'],
                'description' => $validated['description'] ?? null,
                'header_image' => $validated['header_image'] ?? $form->header_image,
                'status' => $validated['status'],
                'access_code' => !empty($validated['access_code']) ? strtoupper(trim($validated['access_code'])) : null,
                'closes_at' => $validated['closes_at'] ?? null,
                'success_message' => $validated['success_message'] ?? null,

                'has_certificate' => $validated['has_certificate'],
                'certificate_status' => $validated['certificate_status'],
                'certificate_event_name' => $validated['certificate_event_name'] ?? null,
                'certificate_event_date' => $validated['certificate_event_date'] ?? null,
                'certificate_event_location' => $validated['certificate_event_location'] ?? null,
                'certificate_total_jp' => $validated['certificate_total_jp'] ?? null,
                'certificate_number_pattern' => $validated['certificate_number_pattern'] ?? null,
                'certificate_signer1_name' => $validated['certificate_signer1_name'] ?? 'Prof. Dr. Iskandar Nazari, M.Pd',
                'certificate_signer1_role' => $validated['certificate_signer1_role'] ?? 'Direktur Ruhiology Institute',
                'certificate_signer1_signature' => $validated['certificate_signer1_signature'] ?? $form->certificate_signer1_signature,
                'certificate_signer2_name' => $validated['certificate_signer2_name'] ?? null,
                'certificate_signer2_role' => $validated['certificate_signer2_role'] ?? null,
                'certificate_signer2_signature' => $validated['certificate_signer2_signature'] ?? $form->certificate_signer2_signature,
                'certificate_partner_logo' => $validated['certificate_partner_logo'] ?? $form->certificate_partner_logo,
                'certificate_curriculum' => $validated['certificate_curriculum'],
                'certificate_name_field_id' => $validated['certificate_name_field_id'] ?? null,
                'certificate_phone_field_id' => $validated['certificate_phone_field_id'] ?? null,
                'certificate_inst_field_id' => $validated['certificate_inst_field_id'] ?? null,
            ]);

            // Sync dynamic fields: delete old and recreate with preserved IDs if needed
            $form->fields()->delete();

            $order = 1;
            foreach ($request->input('fields', []) as $f) {
                if (empty($f['label'])) continue;

                $rawOptions = $f['options'] ?? null;
                $optionsArray = null;
                if (!empty($rawOptions)) {
                    if (is_array($rawOptions)) {
                        $optionsArray = array_values(array_filter(array_map('trim', $rawOptions)));
                    } else {
                        $optionsArray = array_values(array_filter(array_map('trim', preg_split('/[\r\n,]+/', (string) $rawOptions))));
                    }
                }

                CustomFormField::create([
                    'custom_form_id' => $form->id,
                    'label' => trim($f['label']),
                    'description' => !empty($f['description']) ? trim($f['description']) : null,
                    'field_type' => $f['field_type'] ?? 'text',
                    'options' => $optionsArray,
                    'is_required' => !empty($f['is_required']) && ($f['is_required'] === true || $f['is_required'] === '1' || $f['is_required'] === 1),
                    'placeholder' => !empty($f['placeholder']) ? trim($f['placeholder']) : null,
                    'order' => $order++,
                ]);
            }

            AuditLogService::log(
                action: 'update_custom_form',
                module: 'Forms',
                recordType: 'CustomForm',
                recordId: (string) $form->id,
                changes: [
                    'title' => $form->title,
                    'slug' => $form->slug,
                    'has_certificate' => $form->has_certificate,
                ]
            );
        });

        return redirect()->route('admin.forms.index')->with('success', 'Formulir berhasil diperbarui!');
    }

    public function destroy(CustomForm $form)
    {
        $title = $form->title;
        $formId = (string) $form->id;
        $form->delete();

        AuditLogService::log(
            action: 'delete_custom_form',
            module: 'Forms',
            recordType: 'CustomForm',
            recordId: $formId,
            changes: [
                'title' => $title,
            ]
        );

        return back()->with('success', "Formulir \"{$title}\" berhasil dihapus.");
    }

    public function toggleStatus(CustomForm $form)
    {
        $newStatus = $form->status === 'published' ? 'closed' : 'published';
        $form->update(['status' => $newStatus]);

        return back()->with('success', "Status formulir berhasil diubah menjadi: " . strtoupper($newStatus));
    }

    public function toggleCertificateRelease(CustomForm $form)
    {
        $newStatus = $form->certificate_status === 'released' ? 'locked' : 'released';
        $form->update([
            'certificate_status' => $newStatus,
            'certificate_released_at' => $newStatus === 'released' ? now() : $form->certificate_released_at,
        ]);

        $msg = $newStatus === 'released'
            ? "Akses Sertifikat Resmi untuk \"{$form->title}\" BERHASIL DIBUKA! Peserta kini dapat mengunduh sertifikat mereka."
            : "Akses Sertifikat Resmi untuk \"{$form->title}\" BERHASIL DIKUNCI.";

        return back()->with('success', $msg);
    }

    public function responses(Request $request, CustomForm $form)
    {
        $form->load(['fields']);

        $query = $form->responses()->with(['answers.field'])->latest();

        if ($request->filled('q')) {
            $q = trim($request->input('q'));
            $query->where(function ($w) use ($q) {
                $w->where('response_code', 'like', "%{$q}%")
                  ->orWhere('certificate_number', 'like', "%{$q}%")
                  ->orWhere('participant_name_cache', 'like', "%{$q}%")
                  ->orWhereHas('answers', function ($a) use ($q) {
                      $a->where('answer_value', 'like', "%{$q}%");
                  });
            });
        }

        $responses = $query->paginate(20)->withQueryString();

        return view('admin.forms.responses', compact('form', 'responses'));
    }

    public function destroyResponse(CustomFormResponse $response)
    {
        $formId = $response->custom_form_id;
        $code = $response->response_code;
        $response->delete();

        return back()->with('success', "Respon {$code} berhasil dihapus.");
    }

    public function exportCsv(CustomForm $form)
    {
        $form->load(['fields', 'responses.answers.field']);
        $fields = $form->fields;
        $responses = $form->responses()->with(['answers.field'])->oldest()->get();

        $filename = 'Respon_' . Str::slug($form->title) . '_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($form, $fields, $responses) {
            $file = fopen('php://output', 'w');
            
            // Output UTF-8 BOM for Microsoft Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header Row
            $headerRow = ['No', 'Kode Respon', 'No. Sertifikat', 'Nama Peserta', 'Nomor WA', 'Waktu Pengisian', 'IP Address'];
            foreach ($fields as $field) {
                $headerRow[] = $field->label;
            }
            fputcsv($file, $headerRow);

            // Data Rows
            $no = 1;
            foreach ($responses as $r) {
                $answersMap = $r->answers->keyBy('field_id');
                $row = [
                    $no++,
                    $r->response_code,
                    $r->certificate_number ?? '-',
                    $r->participant_name,
                    $r->participant_phone ?? '-',
                    $r->submitted_at ? $r->submitted_at->format('d/m/Y H:i:s') : '-',
                    $r->ip_address ?? '-',
                ];

                foreach ($fields as $field) {
                    $ans = $answersMap->get($field->id);
                    $val = $ans ? $ans->formatted_answer : '-';
                    $row[] = $val;
                }

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
