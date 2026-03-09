<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\TokenMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'nim' => 'required|string',
            'password' => 'required|string',
        ]);

        $mahasiswa = Mahasiswa::where('nim', $request->nim)->first();

        if (!$mahasiswa || !$mahasiswa->verifyPassword($request->password)) {
            return response()->json([
                'success' => false,
                'message' => 'NIM atau password salah.',
            ], 401);
        }

        if ($mahasiswa->status_aktif !== 'Aktif') {
            return response()->json([
                'success' => false,
                'message' => "Akun Anda berstatus {$mahasiswa->status_aktif}. Hubungi admin.",
            ], 403);
        }

        // Buat token baru
        $token = Str::random(64) . time();

        TokenMahasiswa::where('nim', $mahasiswa->nim)
            ->where('is_active', 1)
            ->update(['is_active' => 0]);

        TokenMahasiswa::create([
            'nim' => $mahasiswa->nim,
            'token' => $token,
            'device_info' => $request->header('User-Agent'),
            'ip_address' => $request->ip(),
            'expired_at' => now()->addDays(7),
            'is_active' => 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'data' => [
                'token' => $token,
                'expires_at' => now()->addDays(7)->toIso8601String(),
                'mahasiswa' => [
                    'nim' => $mahasiswa->nim,
                    'nama' => $mahasiswa->nama,
                    'prodi' => $mahasiswa->prodi,
                    'kelas' => $mahasiswa->kelas,
                    'angkatan' => $mahasiswa->angkatan,
                    'semester_sekarang' => $mahasiswa->semester_sekarang,
                    'foto' => $mahasiswa->foto,
                    'status_aktif' => $mahasiswa->status_aktif,
                    'email' => $mahasiswa->email,
                ],
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $token = $request->bearerToken();

        if ($token) {
            TokenMahasiswa::where('token', $token)->update(['is_active' => 0]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.',
        ]);
    }

    public function me(Request $request)
    {
        $mahasiswa = $request->get('auth_mahasiswa');

        return response()->json([
            'success' => true,
            'data' => $mahasiswa->load('dosenPA'),
        ]);
    }

    public function refreshToken(Request $request)
    {
        $oldToken = $request->bearerToken();
        $nim = $request->attributes->get('auth_nim');

        TokenMahasiswa::where('token', $oldToken)->update(['is_active' => 0]);

        $newToken = Str::random(64) . time();

        TokenMahasiswa::create([
            'nim' => $nim,
            'token' => $newToken,
            'device_info' => $request->header('User-Agent'),
            'ip_address' => $request->ip(),
            'expired_at' => now()->addDays(7),
            'is_active' => 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Token diperbarui.',
            'data' => [
                'token' => $newToken,
                'expires_at' => now()->addDays(7)->toIso8601String(),
            ],
        ]);
    }

    // ── LUPA PASSWORD — Langkah 1: kirim kode ke email ──────────
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $mahasiswa = Mahasiswa::where('email', $request->email)->first();

        if (!$mahasiswa) {
            return response()->json(['success' => false, 'message' => 'Email tidak ditemukan.'], 404);
        }

        // Buat kode 6 digit
        $kode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Simpan ke Cache (10 menit)
        Cache::put('reset_code_' . $mahasiswa->nim, $kode, now()->addMinutes(10));

        // Simpan juga ke tabel mahasiswa sebagai fallback
        $mahasiswa->update([
            'reset_token'         => $kode,
            'reset_token_expired' => now()->addMinutes(10),
        ]);

        // Kirim email
        $emailTerkirim = false;
        try {
            Mail::raw(
                "Kode verifikasi reset password SIAKAD Anda: {$kode}\n\nKode berlaku 10 menit.\nAbaikan jika tidak merasa meminta reset password.",
                function ($m) use ($mahasiswa) {
                    $m->to($mahasiswa->email, $mahasiswa->nama)
                      ->subject('Kode Reset Password SIAKAD');
                }
            );
            $emailTerkirim = true;
        } catch (\Exception $e) {
            Log::warning('Email reset password gagal: ' . $e->getMessage());
        }

        $response = [
            'success' => true,
            'message' => $emailTerkirim
                ? 'Kode verifikasi telah dikirim ke email Anda.'
                : 'Kode verifikasi dibuat. Email tidak terkonfigurasi, gunakan kode di bawah.',
            'nim'     => $mahasiswa->nim,
        ];

        // Tampilkan kode jika email gagal atau MAIL_MAILER=log/array
        $mailer = config('mail.default');
        if (!$emailTerkirim || in_array($mailer, ['log', 'array', 'null'])) {
            $response['kode_verifikasi'] = $kode;
            $response['catatan'] = 'Kode ditampilkan karena email belum dikonfigurasi.';
        }

        return response()->json($response);
    }

    // ── LUPA PASSWORD — Langkah 2: verifikasi kode ──────────────
    public function verifyCode(Request $request)
    {
        $request->validate([
            'nim'  => 'required|string',
            'kode' => 'required|string|size:6',
        ]);

        $kodeInput = $request->kode;

        // Cek dari Cache dulu
        $savedCache = Cache::get('reset_code_' . $request->nim);

        // Fallback: cek dari kolom reset_token di tabel mahasiswa
        $mahasiswa    = Mahasiswa::where('nim', $request->nim)->first();
        $savedDb      = $mahasiswa?->reset_token;
        $expiredDb    = $mahasiswa?->reset_token_expired;
        $dbMasihValid = $expiredDb && now()->lt($expiredDb);

        $valid = ($savedCache && $savedCache === $kodeInput)
              || ($savedDb && $savedDb === $kodeInput && $dbMasihValid);

        if (!$valid) {
            return response()->json(['success' => false, 'message' => 'Kode tidak valid atau sudah kadaluarsa.'], 422);
        }

        // Tandai sudah diverifikasi
        Cache::put('reset_verified_' . $request->nim, true, now()->addMinutes(10));

        return response()->json(['success' => true, 'message' => 'Kode valid. Silakan buat password baru.']);
    }

    // ── LUPA PASSWORD — Langkah 3: simpan password baru ─────────
    public function resetPassword(Request $request)
    {
        $request->validate([
            'nim'                  => 'required|string',
            'password'             => 'required|string|min:6',
            'password_confirmation'=> 'required|same:password',
        ]);

        $verified = Cache::get('reset_verified_' . $request->nim);
        if (!$verified) {
            return response()->json(['success' => false, 'message' => 'Sesi reset tidak valid. Mulai ulang proses.'], 422);
        }

        $mahasiswa = Mahasiswa::where('nim', $request->nim)->first();
        if (!$mahasiswa) {
            return response()->json(['success' => false, 'message' => 'Mahasiswa tidak ditemukan.'], 404);
        }

        $mahasiswa->update(['password' => Hash::make($request->password)]);

        Cache::forget('reset_code_'     . $request->nim);
        Cache::forget('reset_verified_' . $request->nim);

        return response()->json(['success' => true, 'message' => 'Password berhasil diperbarui. Silakan login.']);
    }   
}