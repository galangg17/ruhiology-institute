<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminAccess
{
    public function handle(Request $request, Closure $next, ?string $roleRequired = null): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (!$user->isAdmin()) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk menguji halaman administratif.');
        }

        if ($roleRequired && !$user->hasRole($roleRequired)) {
            abort(403, 'Akses Ditolak: Role Anda tidak mencukupi untuk fitur ini.');
        }

        return $next($request);
    }
}
