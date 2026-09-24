@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold font-serif text-slate-900">Audit Trail Log Sistem</h2>
            <p class="text-xs text-slate-500">Catatan riwayat aktivitas penting (Read-Only) seluruh user dan administrator.</p>
        </div>
    </div>

    <!-- Filter & Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 bg-slate-50 border-b border-slate-200">
            <form action="{{ route('admin.audit_logs.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama user / record..." class="px-3 py-1.5 rounded border border-slate-300">
                <select name="module" class="px-3 py-1.5 rounded border border-slate-300">
                    <option value="">Semua Modul</option>
                    <option value="Assessment" {{ request('module') === 'Assessment' ? 'selected' : '' }}>Assessment</option>
                    <option value="Training" {{ request('module') === 'Training' ? 'selected' : '' }}>Training</option>
                    <option value="Store" {{ request('module') === 'Store' ? 'selected' : '' }}>Store</option>
                    <option value="System" {{ request('module') === 'System' ? 'selected' : '' }}>System</option>
                    <option value="User" {{ request('module') === 'User' ? 'selected' : '' }}>User</option>
                </select>
                <select name="action" class="px-3 py-1.5 rounded border border-slate-300">
                    <option value="">Semua Aksi</option>
                    <option value="create" {{ request('action') === 'create' ? 'selected' : '' }}>Create</option>
                    <option value="update" {{ request('action') === 'update' ? 'selected' : '' }}>Update</option>
                    <option value="delete" {{ request('action') === 'delete' ? 'selected' : '' }}>Delete</option>
                    <option value="login" {{ request('action') === 'login' ? 'selected' : '' }}>Login</option>
                </select>
                <button type="submit" class="px-4 py-1.5 bg-slate-800 text-white font-bold rounded">Filter Log</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 text-slate-600 uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="p-4">Waktu Log</th>
                        <th class="p-4">User Executor</th>
                        <th class="p-4">Modul</th>
                        <th class="p-4">Aksi</th>
                        <th class="p-4">Record & Perubahan JSON</th>
                        <th class="p-4">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($logs as $log)
                        <tr class="hover:bg-slate-50/80">
                            <td class="p-4 text-slate-600 font-mono text-[11px]">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                            <td class="p-4 font-bold text-slate-900">{{ $log->user_name ?? 'System' }}</td>
                            <td class="p-4"><span class="px-2 py-0.5 bg-slate-100 font-bold rounded uppercase text-[10px]">{{ $log->module }}</span></td>
                            <td class="p-4 font-bold text-amber-700 uppercase text-[11px]">{{ $log->action }}</td>
                            <td class="p-4 text-slate-700 max-w-md truncate font-mono text-[10px]">
                                {{ $log->record_type }} #{{ $log->record_id }} — {{ json_encode($log->changes_json) }}
                            </td>
                            <td class="p-4 text-slate-400 font-mono text-[10px]">{{ $log->ip_address }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
