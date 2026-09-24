<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\Regency;
use App\Models\School;
use App\Models\University;
use App\Models\Faculty;
use App\Models\StudyProgram;
use App\Models\Occupation;
use App\Models\PendingInstitution;
use App\Services\MasterDataImportService;
use Illuminate\Http\Request;
use Exception;

class AdminMasterDataController extends Controller
{
    protected MasterDataImportService $importService;

    public function __construct(MasterDataImportService $importService)
    {
        $this->importService = $importService;
    }

    public function index(Request $request)
    {
        $tab = $request->query('tab', 'provinces');

        $provinces = Province::withCount(['regencies', 'schools', 'universities'])->orderBy('name', 'asc')->paginate(15, ['*'], 'prov_page');
        $regencies = Regency::with(['province'])->orderBy('name', 'asc')->paginate(15, ['*'], 'reg_page');
        $schools = School::with(['province', 'regency'])->orderBy('name', 'asc')->paginate(15, ['*'], 'sch_page');
        $universities = University::with(['province', 'regency'])->withCount(['faculties', 'studyPrograms'])->orderBy('name', 'asc')->paginate(15, ['*'], 'uni_page');
        $occupations = Occupation::orderBy('name', 'asc')->paginate(15, ['*'], 'occ_page');
        $pendingInstitutions = PendingInstitution::orderBy('created_at', 'desc')->paginate(15, ['*'], 'pending_page');

        return view('admin.master_data.index', compact(
            'tab', 'provinces', 'regencies', 'schools', 'universities', 'occupations', 'pendingInstitutions'
        ));
    }

    public function storeProvince(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'unique:provinces,code'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        Province::create($validated + ['country_id' => 1, 'status' => 'active']);

        return back()->with('success', 'Provinsi berhasil ditambahkan.');
    }

    public function storeRegency(Request $request)
    {
        $validated = $request->validate([
            'province_id' => ['required', 'exists:provinces,id'],
            'code' => ['required', 'string', 'unique:regencies,code'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:Kabupaten,Kota'],
        ]);

        Regency::create($validated + ['status' => 'active']);

        return back()->with('success', 'Kabupaten/Kota berhasil ditambahkan.');
    }

    public function storeSchool(Request $request)
    {
        $validated = $request->validate([
            'province_id' => ['nullable', 'exists:provinces,id'],
            'regency_id' => ['nullable', 'exists:regencies,id'],
            'level' => ['nullable', 'in:SD,SMP,SMA,SMK,Sederajat'],
            'npsn' => ['nullable', 'string'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        School::create($validated + ['level' => $validated['level'] ?? 'SMA', 'status' => 'active']);

        return back()->with('success', 'Sekolah berhasil ditambahkan.');
    }

    public function storeUniversity(Request $request)
    {
        $validated = $request->validate([
            'province_id' => ['nullable', 'exists:provinces,id'],
            'regency_id' => ['nullable', 'exists:regencies,id'],
            'code' => ['nullable', 'string'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        if (empty($validated['code'])) {
            $validated['code'] = 'UNIV-' . strtoupper(substr(md5($validated['name']), 0, 6));
        }

        University::create($validated + ['status' => 'active']);

        return back()->with('success', 'Perguruan Tinggi berhasil ditambahkan.');
    }

    public function storeOccupation(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:occupations,name'],
        ]);

        Occupation::create($validated + ['status' => 'active']);

        return back()->with('success', 'Pekerjaan berhasil ditambahkan.');
    }

    public function updatePendingStatus(Request $request, PendingInstitution $pending)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:verified,rejected'],
        ]);

        $pending->update(['status' => $validated['status']]);

        if ($validated['status'] === 'verified') {
            $name = trim($pending->name);
            if ($pending->category === 'Pelajar') {
                $school = School::where('name', $name)->first();
                if (!$school) {
                    School::create([
                        'name' => $name,
                        'level' => 'SMA',
                        'status' => 'active',
                    ]);
                }
            } else {
                $uni = University::where('name', $name)->first();
                if (!$uni) {
                    University::create([
                        'name' => $name,
                        'code' => 'UNIV-' . strtoupper(substr(md5($name), 0, 6)),
                        'status' => 'active',
                    ]);
                }
            }
        }

        return back()->with('success', 'Status usulan institusi berhasil diperbarui dan disinkronkan ke Master Data.');
    }

    public function importCsv(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:provinces,regencies,schools,universities,occupations'],
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:10240'],
        ]);

        try {
            $file = $request->file('csv_file');
            $res = $this->importService->importCsv($validated['type'], $file->getRealPath());

            return back()->with('success', "Import {$validated['type']} Berhasil! (Ditambahkan: {$res['inserted']}, Diperbarui: {$res['updated']})");
        } catch (Exception $e) {
            return back()->with('error', 'Gagal Import: ' . $e->getMessage());
        }
    }
}
