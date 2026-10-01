<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->with('institution');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%')
                  ->orWhere('email', 'like', '%' . $request->q . '%');
        }

        $users = $query->latest()->paginate(15);
        $institutions = Institution::all();

        return view('admin.users.index', compact('users', 'institutions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', Password::defaults()],
            'role' => ['required', 'in:super_admin,admin,content_manager,assessment_manager,training_manager,store_manager,consultation_manager,participant'],
            'institution_id' => ['nullable', 'exists:institutions,id'],
            'phone' => ['nullable', 'string', 'max:30'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);

        AuditLogService::log(
            action: 'create_user',
            module: 'User',
            recordType: 'User',
            recordId: (string) $user->id,
            changes: ['email' => $user->email, 'role' => $user->role]
        );

        return back()->with('success', 'User admin/staff baru berhasil ditambahkan.');
    }

    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => ['required', 'in:super_admin,admin,content_manager,assessment_manager,training_manager,store_manager,consultation_manager,participant'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $user->update($validated);

        AuditLogService::log(
            action: 'update_user_role',
            module: 'User',
            recordType: 'User',
            recordId: (string) $user->id,
            changes: $validated
        );

        return back()->with('success', 'Role user berhasil diperbarui.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', 'in:super_admin,admin,content_manager,assessment_manager,training_manager,store_manager,consultation_manager,participant'],
            'institution_id' => ['nullable', 'exists:institutions,id'],
            'phone' => ['nullable', 'string', 'max:30'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        AuditLogService::log(
            action: 'update_user',
            module: 'User',
            recordType: 'User',
            recordId: (string) $user->id,
            changes: ['name' => $user->name, 'email' => $user->email, 'role' => $user->role]
        );

        return back()->with('success', 'Data user admin "' . $user->name . '" berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Gagal menghapus: Anda tidak dapat menghapus akun Anda sendiri yang sedang digunakan saat ini.');
        }

        $userName = $user->name;
        $userEmail = $user->email;
        $userId = (string) $user->id;

        $user->delete();

        AuditLogService::log(
            action: 'delete_user',
            module: 'User',
            recordType: 'User',
            recordId: $userId,
            changes: ['deleted_email' => $userEmail]
        );

        return back()->with('success', 'Akun user "' . $userName . '" (' . $userEmail . ') berhasil dihapus dari sistem.');
    }
}
