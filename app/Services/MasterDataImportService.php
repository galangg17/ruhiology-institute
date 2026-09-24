<?php

namespace App\Services;

use App\Models\Province;
use App\Models\Regency;
use App\Models\School;
use App\Models\University;
use App\Models\Faculty;
use App\Models\StudyProgram;
use App\Models\Occupation;
use Illuminate\Support\Facades\DB;
use Exception;

class MasterDataImportService
{
    /**
     * Parse and import CSV master data batch.
     */
    public function importCsv(string $type, string $filePath): array
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new Exception("File CSV tidak dapat dibaca atau tidak ditemukan.");
        }

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            throw new Exception("Gagal membuka stream file CSV.");
        }

        $header = fgetcsv($handle, 2000, ',');
        if (!$header) {
            fclose($handle);
            throw new Exception("Header CSV kosong atau format tidak valid.");
        }

        // Clean UTF-8 BOM if present
        $header[0] = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $header[0]);
        $header = array_map('trim', array_map('strtolower', $header));

        $insertedCount = 0;
        $updatedCount = 0;
        $errors = [];

        DB::beginTransaction();
        try {
            $rowNum = 1;
            while (($row = fgetcsv($handle, 2000, ',')) !== false) {
                $rowNum++;
                if (count($row) < count($header)) continue;

                $data = array_combine($header, array_map('trim', $row));

                switch ($type) {
                    case 'provinces':
                        $code = $data['code'] ?? null;
                        $name = $data['name'] ?? null;
                        $countryId = $data['country_id'] ?? 1;

                        if ($code && $name) {
                            $prov = Province::updateOrCreate(
                                ['code' => $code],
                                ['country_id' => $countryId, 'name' => $name, 'status' => 'active']
                            );
                            if ($prov->wasRecentlyCreated) $insertedCount++; else $updatedCount++;
                        }
                        break;

                    case 'regencies':
                        $code = $data['code'] ?? null;
                        $name = $data['name'] ?? null;
                        $provId = $data['province_id'] ?? null;
                        $regType = $data['type'] ?? 'Kabupaten';

                        if ($code && $name && $provId) {
                            $reg = Regency::updateOrCreate(
                                ['code' => $code],
                                ['province_id' => $provId, 'name' => $name, 'type' => $regType, 'status' => 'active']
                            );
                            if ($reg->wasRecentlyCreated) $insertedCount++; else $updatedCount++;
                        }
                        break;

                    case 'schools':
                        $name = $data['name'] ?? null;
                        $provId = $data['province_id'] ?? null;
                        $regId = $data['regency_id'] ?? null;
                        $level = $data['level'] ?? 'SMA';

                        if ($name && $provId && $regId) {
                            $sch = School::updateOrCreate(
                                ['name' => $name, 'regency_id' => $regId],
                                ['province_id' => $provId, 'level' => $level, 'npsn' => $data['npsn'] ?? null, 'status' => 'active']
                            );
                            if ($sch->wasRecentlyCreated) $insertedCount++; else $updatedCount++;
                        }
                        break;

                    case 'universities':
                        $name = $data['name'] ?? null;
                        $provId = $data['province_id'] ?? null;
                        $regId = $data['regency_id'] ?? null;

                        if ($name && $provId && $regId) {
                            $uni = University::updateOrCreate(
                                ['name' => $name],
                                ['province_id' => $provId, 'regency_id' => $regId, 'code' => $data['code'] ?? null, 'status' => 'active']
                            );
                            if ($uni->wasRecentlyCreated) $insertedCount++; else $updatedCount++;
                        }
                        break;

                    case 'occupations':
                        $name = $data['name'] ?? null;
                        if ($name) {
                            $occ = Occupation::updateOrCreate(
                                ['name' => $name],
                                ['status' => 'active']
                            );
                            if ($occ->wasRecentlyCreated) $insertedCount++; else $updatedCount++;
                        }
                        break;
                }
            }

            fclose($handle);
            DB::commit();

            return [
                'success' => true,
                'type' => $type,
                'inserted' => $insertedCount,
                'updated' => $updatedCount,
                'errors' => $errors,
            ];
        } catch (Exception $e) {
            fclose($handle);
            DB::rollBack();
            throw new Exception("Gagal mengimpor data CSV pada baris {$rowNum}: " . $e->getMessage());
        }
    }
}
