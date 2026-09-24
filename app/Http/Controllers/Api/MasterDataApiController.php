<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\Regency;
use App\Models\School;
use App\Models\University;
use App\Models\Faculty;
use App\Models\StudyProgram;
use App\Models\Occupation;
use App\Models\PendingInstitution;
use Illuminate\Http\Request;

class MasterDataApiController extends Controller
{
    public function provinces(Request $request)
    {
        $countryId = $request->query('country_id', 1);
        $search = trim($request->query('q', ''));
        $limit = min((int) $request->query('limit', 100), 200);

        $query = Province::where('status', 'active')
            ->where(function ($q) use ($countryId) {
                if ($countryId) {
                    $q->where('country_id', $countryId);
                }
            });

        if ($search !== '') {
            $query->where('name', 'like', "%{$search}%");
        }

        $items = $query->orderBy('name', 'asc')->limit($limit)->get(['id', 'code', 'name']);

        return response()->json([
            'status' => 'success',
            'data' => $items,
        ]);
    }

    public function regencies(Request $request)
    {
        $provinceId = $request->query('province_id');
        $search = trim($request->query('q', ''));
        $limit = min((int) $request->query('limit', 100), 200);


        if (!$provinceId) {
            return response()->json(['status' => 'success', 'data' => []]);
        }

        $query = Regency::where('province_id', $provinceId)
            ->where('status', 'active');

        if ($search !== '') {
            $query->where('name', 'like', "%{$search}%");
        }

        $items = $query->orderBy('name', 'asc')->limit($limit)->get(['id', 'code', 'name', 'type']);

        return response()->json([
            'status' => 'success',
            'data' => $items,
        ]);
    }

    public function schools(Request $request)
    {
        $provinceId = $request->query('province_id');
        $regencyId = $request->query('regency_id');
        $level = $request->query('level');
        $search = trim($request->query('q', ''));
        $limit = min((int) $request->query('limit', 20), 50);

        $query = School::where('status', 'active');

        if ($search !== '') {
            $query->where('name', 'like', "%{$search}%");
            if ($provinceId) {
                $query->where('province_id', $provinceId);
            }
        } else {
            if ($provinceId) {
                $query->where('province_id', $provinceId);
            }
            if ($regencyId) {
                $query->where('regency_id', $regencyId);
            }
            if ($level) {
                $query->where('level', $level);
            }
        }

        $items = $query->orderBy('name', 'asc')->limit($limit)->get(['id', 'name', 'level', 'npsn']);

        return response()->json([
            'status' => 'success',
            'data' => $items,
        ]);
    }

    public function universities(Request $request)
    {
        $provinceId = $request->query('province_id');
        $search = trim($request->query('q', ''));
        $limit = min((int) $request->query('limit', 20), 50);

        $query = University::where('status', 'active');

        if ($search !== '') {
            $query->where('name', 'like', "%{$search}%");
        } else {
            if ($provinceId) {
                $query->where('province_id', $provinceId);
            }
        }

        $items = $query->orderBy('name', 'asc')->limit($limit)->get(['id', 'name', 'code']);

        return response()->json([
            'status' => 'success',
            'data' => $items,
        ]);
    }

    public function faculties(Request $request)
    {
        $universityId = $request->query('university_id');
        $search = trim($request->query('q', ''));
        $limit = min((int) $request->query('limit', 15), 50);

        if (!$universityId) {
            return response()->json(['status' => 'success', 'data' => []]);
        }

        $query = Faculty::where('university_id', $universityId)
            ->where('status', 'active');

        if ($search !== '') {
            $query->where('name', 'like', "%{$search}%");
        }

        $items = $query->orderBy('name', 'asc')->limit($limit)->get(['id', 'name', 'code']);

        return response()->json([
            'status' => 'success',
            'data' => $items,
        ]);
    }

    public function studyPrograms(Request $request)
    {
        $universityId = $request->query('university_id');
        $facultyId = $request->query('faculty_id');
        $search = trim($request->query('q', ''));
        $limit = min((int) $request->query('limit', 15), 50);

        if (!$universityId && !$facultyId) {
            return response()->json(['status' => 'success', 'data' => []]);
        }

        $query = StudyProgram::where('status', 'active');

        if ($universityId) {
            $query->where('university_id', $universityId);
        }
        if ($facultyId) {
            $query->where('faculty_id', $facultyId);
        }

        if ($search !== '') {
            $query->where('name', 'like', "%{$search}%");
        }

        $items = $query->orderBy('name', 'asc')->limit($limit)->get(['id', 'name', 'code']);

        return response()->json([
            'status' => 'success',
            'data' => $items,
        ]);
    }

    public function occupations(Request $request)
    {
        $search = trim($request->query('q', ''));
        $limit = min((int) $request->query('limit', 15), 50);

        $query = Occupation::where('status', 'active');

        if ($search !== '') {
            $query->where('name', 'like', "%{$search}%");
        }

        $items = $query->orderBy('name', 'asc')->limit($limit)->get(['id', 'name']);

        return response()->json([
            'status' => 'success',
            'data' => $items,
        ]);
    }

    public function storePendingInstitution(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:50'],
            'location' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $pending = PendingInstitution::create($validated + ['status' => 'pending']);

        return response()->json([
            'status' => 'success',
            'message' => 'Usulan institusi Anda telah disimpan untuk verifikasi admin.',
            'data' => $pending,
        ]);
    }

    public function instantUniversity(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'province_id' => ['nullable', 'exists:provinces,id'],
            'regency_id' => ['nullable', 'exists:regencies,id'],
        ]);

        $name = trim($validated['name']);
        
        $university = University::where('name', $name)->first();
        if (!$university) {
            $code = 'UNIV-' . strtoupper(substr(md5($name), 0, 6));
            $university = University::create([
                'name' => $name,
                'code' => $code,
                'province_id' => $validated['province_id'] ?? null,
                'regency_id' => $validated['regency_id'] ?? null,
                'status' => 'active',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Perguruan Tinggi berhasil ditambahkan.',
            'data' => $university,
        ]);
    }
}
