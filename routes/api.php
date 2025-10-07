<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Rekening;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\AuthController;


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
Route::post('/nasabah/login', [AuthController::class, 'login']);


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
