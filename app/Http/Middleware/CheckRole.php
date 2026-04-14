<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $userRole = auth()->user()->role;

        if (!in_array($userRole, $roles)) {
            // Redirect unauthorized users to their respective dashboards based on their actual role
            if ($userRole === 'admin') {
                return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk halaman tersebut.');
            } elseif ($userRole === 'it_support') {
                return redirect()->route('it.dashboard')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk halaman tersebut.');
            } else {
                return redirect()->route('user.dashboard')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk halaman tersebut.');
            }
        }

        return $next($request);
    }
}
