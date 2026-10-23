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
        $query = CustomForm::withCount('responses')->latest();

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

        $forms = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => CustomForm::count(),
            'published' => CustomForm::where('status', 'published')->count(),
            'closed' => CustomForm::where('status', 'closed')->count(),
            'total_responses' => CustomFormResponse::count(),
        ];

        return view('admin.forms.index', compact('forms', 'stats'));
    }

    public function create()
    {
        return view('admin.forms.create');
    }

    public function store(Request $request)
    {
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
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = CustomForm::generateUniqueSlug($validated['title']);
        } else {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        if ($request->hasFile('header_file')) {
            $file = $request->file('header_file');
            $filename = 'form_hdr_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $path = public_path('images/forms');
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
            $file->move($path, $filename);
            $validated['header_image'] = asset('images/forms/' . $filename);
        }

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
                ]
            );
        });

        return redirect()->route('admin.forms.index')->with('success', 'Formulir kuisioner baru berhasil dibuat dan siap dibagikan!');
    }

    public function edit(CustomForm $form)
    {
        $form->load('fields');
        return view('admin.forms.edit', compact('form'));
    }

    public function update(Request $request, CustomForm $form)
    {
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
        ]);

        $validated['slug'] = Str::slug($validated['slug']);

        if ($request->hasFile('header_file')) {
            $file = $request->file('header_file');
            $filename = 'form_hdr_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $path = public_path('images/forms');
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
            $file->move($path, $filename);
            $validated['header_image'] = asset('images/forms/' . $filename);
        }

        DB::transaction(function () use ($form, $validated, $request) {
            $form->update([
                'title' => trim($validated['title']),
                'slug' => $validated['slug'],
                'description' => $validated['description'] ?? null,
                'header_image' => $validated['header_image'] ?? $form->header_image,
                'status' => $validated['status'],
                'access_code' => !empty($validated['access_code']) ? strtoupper(trim($validated['access_code'])) : null,
                'closes_at' => $validated['closes_at'] ?? null,
                'success_message' => $validated['success_message'] ?? $form->success_message,
            ]);

            // Re-sync fields cleanly
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

    public function responses(Request $request, CustomForm $form)
    {
        $form->load(['fields']);

        $query = $form->responses()->with(['answers.field'])->latest();

        if ($request->filled('q')) {
            $q = trim($request->input('q'));
            $query->where(function ($w) use ($q) {
                $w->where('response_code', 'like', "%{$q}%")
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
            $headerRow = ['No', 'Kode Respon', 'Waktu Pengisian', 'IP Address'];
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
