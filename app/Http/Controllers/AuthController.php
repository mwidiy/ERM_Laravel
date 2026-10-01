<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show the enterprise login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle enterprise authentication attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $throttleKey = Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan login gagal. Silakan coba lagi dalam {$seconds} detik.",
            ]);
        }

        $remember = $request->boolean('remember');

        // Check credentials and ensure account is active
        if (Auth::attempt(array_merge($credentials, ['status' => 'active']), $remember)) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            $user = Auth::user();
            $roleLabel = match ($user->role) {
                'super_admin' => 'Super Administrator (Full Bypass)',
                'admin' => 'Administrator Operasional',
                'project_manager' => 'Project Delivery Manager',
                'finance' => 'Finance & Tax Specialist',
                default => 'Enterprise Consultant',
            };

            return redirect()->intended(route('dashboard'))->with(
                'success',
                "Selamat datang kembali, {$user->name}! Akses terverifikasi sebagai {$roleLabel}."
            );
        }

        RateLimiter::hit($throttleKey, 60);

        return back()->withInput($request->only('email', 'remember'))->withErrors([
            'email' => 'Kredensial tidak valid atau akun dinonaktifkan oleh administrator.',
        ]);
    }

    /**
     * Terminate the authenticated session.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with(
            'success',
            'Sesi kerja Anda telah aman diakhiri. Silakan login kembali untuk melanjutkan.'
        );
    }
}
