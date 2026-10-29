<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman formulir login.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'super_admin') {
                return redirect()->route('home');
            }
            Auth::logout();
        }

        // Self-healing: Pastikan akun Super Admin default tersedia di database
        $this->ensureSuperAdminExists();

        return view('auth.login');
    }

    /**
     * Proses autentikasi login (khusus super_admin).
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Alamat email kedinasan wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $remember)) {
            $user = Auth::user();

            // Batasi HANYA Super Admin untuk saat ini sesuai permintaan
            if ($user->role !== 'super_admin') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()
                    ->withInput($request->only('email', 'remember'))
                    ->withErrors([
                        'email' => 'Akses ditolak. Untuk saat ini hanya akun Super Admin yang diizinkan masuk ke sistem.',
                    ]);
            }

            // Periksa status keaktifan akun
            if (! $user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()
                    ->withInput($request->only('email', 'remember'))
                    ->withErrors([
                        'email' => 'Akun Super Admin ini berstatus tidak aktif. Silakan hubungi administrator.',
                    ]);
            }

            $request->session()->regenerate();

            return redirect()->intended(route('home'))
                ->with('success', 'Selamat datang, '.$user->name.'. Anda berhasil masuk sebagai Super Administrator.');
        }

        return back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => 'Alamat email atau kata sandi yang Anda masukkan tidak sesuai.',
            ]);
    }

    /**
     * Proses keluar (logout).
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil keluar dari sistem Presisi.');
    }

    /**
     * Menjamin akun Super Admin tersedia jika database belum di-seed.
     */
    protected function ensureSuperAdminExists(): void
    {
        $superAdmin = User::query()->where('email', 'superadmin@polda.polri.go.id')->first();

        if (! $superAdmin) {
            User::query()->create([
                'name' => 'Super Administrator SDM',
                'email' => 'superadmin@polda.polri.go.id',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'phone' => '081100000001',
                'is_active' => true,
            ]);
        }
    }
}
