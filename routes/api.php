<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Rekening;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Semua endpoint untuk aplikasi Android kamu dibuat di sini.
| File ini otomatis diprefix dengan /api, jadi route /nasabah/login
| akan diakses melalui domain.com/api/nasabah/login
|
*/

// LOGIN NASABAH
Route::post('/nasabah/login', function (Request $request) {
    $request->validate([
        'nik' => 'required',
        'pin' => 'required',
    ]);

    $nasabah = Rekening::where('nik', $request->nik)->first();

    if (! $nasabah || ! Hash::check($request->pin, $nasabah->pin)) {
        return response()->json([
            'success' => false,
            'message' => 'NIK atau PIN salah',
        ], 401);
    }

    $token = $nasabah->createToken('mobile-token')->plainTextToken;

    return response()->json([
        'success' => true,
        'message' => 'Login berhasil',
        'data' => [
            'token' => $token,
            'nasabah' => $nasabah,
        ]
    ]);
});

// ROUTE TERPROTEKSI
Route::middleware('auth:rekening')->group(function () {
    Route::get('/nasabah/profile', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => $request->user(),
        ]);
    });

    Route::post('/nasabah/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ]);
    });

    

});

Route::get('/tes-api', function () {
    return response()->json(['message' => 'API aktif ✅']);
    });
