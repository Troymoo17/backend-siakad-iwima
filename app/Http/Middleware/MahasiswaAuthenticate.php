<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\TokenMahasiswa;

class MahasiswaAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak ditemukan. Silakan login terlebih dahulu.',
            ], 401);
        }

        $tokenRecord = TokenMahasiswa::where('token', $token)
            ->where('is_active', 1)
            ->where('expired_at', '>', now())
            ->with('mahasiswa')
            ->first();

        if (!$tokenRecord || !$tokenRecord->mahasiswa) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid atau sudah expired. Silakan login ulang.',
            ], 401);
        }

        // ✅ Pakai attributes (bukan merge) agar konsisten dengan request->get() di controller
        $request->attributes->set('auth_mahasiswa', $tokenRecord->mahasiswa);
        $request->attributes->set('auth_nim', $tokenRecord->nim);

        return $next($request);
    }
}
