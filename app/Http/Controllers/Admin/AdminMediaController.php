<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminMediaController extends Controller
{
    public function index(Request $request)
    {
        $query = Media::query();
        if ($request->filled('q')) {
            $query->where('file_name', 'like', '%' . $request->q . '%')
                  ->orWhere('alt_text', 'like', '%' . $request->q . '%');
        }

        $mediaFiles = $query->latest()->paginate(12);

        return view('admin.media.index', compact('mediaFiles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf,docx', 'max:5120'], // 5MB limit
            'alt_text' => ['nullable', 'string', 'max:255'],
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('uploads/media', $fileName, 'public');

        $media = Media::create([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => Storage::url($path),
            'mime_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'alt_text' => $request->alt_text,
        ]);

        AuditLogService::log(
            action: 'upload_media',
            module: 'Media',
            recordType: 'Media',
            recordId: (string) $media->id
        );

        return back()->with('success', 'File media berhasil diunggah.');
    }

    public function destroy(Media $media)
    {
        $media->delete();
        return back()->with('success', 'File media berhasil dihapus.');
    }
}
