<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;
use Inertia\Response;

class PasswordResetLinkController extends Controller
{
    /**
     * Show the password reset link request page.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('auth/forgot-password', [
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nik' => 'required|string|digits:16',
        ]);

        // Cari user berdasarkan NIK
        $user = \App\Models\User::where('nik', $request->nik)->first();
        
        if (!$user) {
            return back()->withErrors(['nik' => 'NIK tidak ditemukan dalam sistem.']);
        }

        // Kirim reset link ke email user
        $status = Password::sendResetLink(['email' => $user->email]);

        return $status == Password::RESET_LINK_SENT
            ? back()->with('status', 'Link reset password telah dikirim ke email yang terdaftar.')
            : back()->withErrors(['nik' => 'Terjadi kesalahan dalam mengirim link reset password.']);
    }
}
