<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request and ensure user has authorized role.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->guest(route('login'));
        }

        // Super Admin bypasses all role restrictions
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Parse any comma-separated or variadic role arguments
        $parsedRoles = [];
        foreach ($roles as $role) {
            foreach (explode(',', $role) as $subRole) {
                $subRole = trim($subRole);
                if ($subRole !== '') {
                    $parsedRoles[] = $subRole;
                }
            }
        }

        // Check if user role is in the allowed roles
        if (!empty($parsedRoles) && !in_array($user->role, $parsedRoles)) {
            $formattedRole = ucwords(str_replace('_', ' ', $user->role));
            return redirect()->route('dashboard')->with(
                'error',
                "Akses Ditolak [403]: Jabatan / Role Anda ({$formattedRole}) tidak memiliki izin otoritas untuk membuka modul tersebut. Hubungi Super Admin untuk penyesuaian hak akses."
            );
        }

        return $next($request);
    }
}
