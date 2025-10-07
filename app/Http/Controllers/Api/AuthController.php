<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rekening;

class AuthController extends Controller
{
    /**
     * Login nasabah menggunakan NIK dan tanggal lahir sebagai PIN.
     */
    public function login(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'pin' => 'required', // format: yyyy-mm-dd
        ]);

        $nasabah = Rekening::where('nik', $request->nik)->first();

        if (! $nasabah) {
            return response()->json([
                'success' => false,
                'message' => 'NIK tidak ditemukan'
            ], 404);
        }

        // Normalisasi format tanggal (boleh pakai 01-01-2000, 2000/01/01, dst)
        $inputPin = str_replace(['/', '.'], '-', $request->pin);
        $inputPin = date('Y-m-d', strtotime($inputPin));

        if ($nasabah->tanggal_lahir !== $inputPin) {
            return response()->json([
                'success' => false,
                'message' => 'Tanggal lahir (PIN) salah'
            ], 401);
        }

        // Buat token Sanctum baru
        $token = $nasabah->createToken('mobile-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'token' => $token,
                'nasabah' => [
                    'id' => $nasabah->id,
                    'nama' => $nasabah->nama,
                    'nik' => $nasabah->nik,
                    'no_rekening' => $nasabah->no_rekening,
                    'telepon' => $nasabah->telepon,
                    'balance' => $nasabah->balance,
                    'points_balance' => $nasabah->points_balance,
                    'formatted_balance' => $nasabah->formatted_balance,
                ],
            ]
        ]);
    }

    /**
     * Mendapatkan profil nasabah yang sedang login.
     */
    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user(),
        ]);
    }

    /**
     * Logout nasabah, hapus token aktif.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ]);
    }
}
