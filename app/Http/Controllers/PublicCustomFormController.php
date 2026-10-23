<?php

namespace App\Http\Controllers;

use App\Models\CustomForm;
use App\Models\CustomFormField;
use App\Models\CustomFormResponse;
use App\Models\CustomFormResponseAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PublicCustomFormController extends Controller
{
    public function show(Request $request, string $slug)
    {
        $form = CustomForm::where('slug', $slug)->with(['fields'])->firstOrFail();

        // Check if form is closed or draft
        if ($form->status === 'draft') {
            abort(404, 'Formulir ini belum dipublikasikan.');
        }

        $isClosed = !$form->isAcceptingResponses();

        // Access code verification
        $requiresPasscode = !empty($form->access_code);
        $passcodeVerified = false;
        if ($requiresPasscode) {
            $sessionKey = 'form_access_' . $form->id;
            if (session($sessionKey) === true) {
                $passcodeVerified = true;
            } elseif ($request->isMethod('post') && $request->has('access_code')) {
                if (strtoupper(trim($request->input('access_code'))) === $form->access_code) {
                    session([$sessionKey => true]);
                    $passcodeVerified = true;
                } else {
                    return back()->with('passcode_error', 'Kode akses formulir tidak cocok. Silakan tanyakan kepada penyelenggara.');
                }
            }
        } else {
            $passcodeVerified = true;
        }

        return view('public.forms.show', compact('form', 'isClosed', 'requiresPasscode', 'passcodeVerified'));
    }

    public function submit(Request $request, string $slug)
    {
        $form = CustomForm::where('slug', $slug)->with(['fields'])->firstOrFail();

        if (!$form->isAcceptingResponses()) {
            return back()->with('error', 'Mohon maaf, formulir ini saat ini telah ditutup dan tidak menerima respon baru.');
        }

        // Passcode verification check
        if (!empty($form->access_code)) {
            $sessionKey = 'form_access_' . $form->id;
            if (session($sessionKey) !== true) {
                return redirect()->route('public.form.show', $form->slug)->with('passcode_error', 'Sesi kode akses Anda telah kedaluwarsa.');
            }
        }

        $fields = $form->fields;
        $rules = [];
        $customAttributes = [];

        foreach ($fields as $field) {
            $fieldKey = 'field_' . $field->id;
            $fieldRules = [];

            if ($field->is_required) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }

            if ($field->field_type === 'number') {
                $fieldRules[] = 'numeric';
            } elseif ($field->field_type === 'date') {
                $fieldRules[] = 'date';
            } elseif ($field->field_type === 'checkbox') {
                $fieldRules[] = 'array';
            } elseif ($field->field_type === 'file') {
                $fieldRules[] = 'file';
                $fieldRules[] = 'max:10240'; // max 10MB
                $fieldRules[] = 'mimes:jpeg,png,jpg,pdf,doc,docx,xls,xlsx,zip';
            } else {
                $fieldRules[] = 'string';
            }

            $rules[$fieldKey] = $fieldRules;
            $customAttributes[$fieldKey] = $field->label;
        }

        $validated = $request->validate($rules, [], $customAttributes);

        $responseCode = CustomFormResponse::generateUniqueCode();

        DB::transaction(function () use ($form, $fields, $request, $responseCode) {
            $response = CustomFormResponse::create([
                'custom_form_id' => $form->id,
                'response_code' => $responseCode,
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 500),
                'submitted_at' => now(),
            ]);

            foreach ($fields as $field) {
                $fieldKey = 'field_' . $field->id;
                $val = null;

                if ($field->field_type === 'file') {
                    if ($request->hasFile($fieldKey)) {
                        $file = $request->file($fieldKey);
                        $filename = 'form_doc_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                        $path = public_path('uploads/forms');
                        if (!file_exists($path)) {
                            mkdir($path, 0755, true);
                        }
                        $file->move($path, $filename);
                        $val = asset('uploads/forms/' . $filename);
                    }
                } elseif ($field->field_type === 'checkbox') {
                    $rawArr = $request->input($fieldKey, []);
                    $val = is_array($rawArr) ? json_encode(array_values($rawArr)) : (string) $rawArr;
                } else {
                    $val = $request->input($fieldKey);
                }

                CustomFormResponseAnswer::create([
                    'response_id' => $response->id,
                    'field_id' => $field->id,
                    'answer_value' => $val !== null ? (string) $val : null,
                ]);
            }
        });

        return redirect()->route('public.form.success', [
            'slug' => $form->slug,
            'response_code' => $responseCode
        ]);
    }

    public function success(string $slug, string $responseCode)
    {
        $form = CustomForm::where('slug', $slug)->firstOrFail();
        $response = CustomFormResponse::where('custom_form_id', $form->id)
            ->where('response_code', $responseCode)
            ->firstOrFail();

        return view('public.forms.success', compact('form', 'response'));
    }
}
