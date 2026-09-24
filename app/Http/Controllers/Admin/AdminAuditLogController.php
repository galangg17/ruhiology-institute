<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AdminAuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::query()->with('user');

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('q')) {
            $query->where('user_name', 'like', '%' . $request->q . '%')
                  ->orWhere('record_id', 'like', '%' . $request->q . '%');
        }

        $logs = $query->latest()->paginate(20);

        return view('admin.audit_logs.index', compact('logs'));
    }
}
