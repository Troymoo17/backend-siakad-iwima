<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PushSubscription;
use App\Models\NotifikasiMahasiswa;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;

class PushController extends Controller
{
    protected PushNotificationService $push;

    public function __construct(PushNotificationService $push)
    {
        $this->push = $push;
    }

    /**
     * Simpan subscription dari browser
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'endpoint'   => 'required|string',
            'public_key' => 'required|string',
            'auth_token' => 'required|string',
        ]);

        $nim = $request->attributes->get('auth_nim');

        // Upsert — jika endpoint sama, update; jika baru, insert
        PushSubscription::updateOrCreate(
            ['endpoint' => $request->endpoint],
            [
                'nim'         => $nim,
                'public_key'  => $request->public_key,
                'auth_token'  => $request->auth_token,
                'device_info' => $request->header('User-Agent'),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi push berhasil diaktifkan.',
        ]);
    }

    /**
     * Hapus subscription (unsubscribe)
     */
    public function unsubscribe(Request $request)
    {
        $request->validate(['endpoint' => 'required|string']);

        PushSubscription::where('endpoint', $request->endpoint)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi push dinonaktifkan.',
        ]);
    }

    /**
     * VAPID public key untuk frontend
     */
    public function vapidKey()
    {
        return response()->json([
            'success'    => true,
            'public_key' => config('app.vapid_public_key', ''),
        ]);
    }

    /**
     * Kirim notifikasi test (untuk demo ngrok)
     */
    public function sendTest(Request $request)
    {
        $nim = $request->attributes->get('auth_nim');

        // Simpan ke tabel notifikasi_mahasiswa dulu
        NotifikasiMahasiswa::create([
            'nim'   => $nim,
            'judul' => '🔔 Test Push Notification',
            'pesan' => 'Push notification SIAKAD berhasil! Kamu akan mendapat notifikasi penting seperti ini.',
            'tipe'  => 'info',
            'link'  => '/notifikasi',
        ]);

        // Kirim push
        $this->push->sendToNim(
            $nim,
            '🔔 Test Push Notification',
            'Push notification SIAKAD berhasil! Kamu akan mendapat notifikasi penting.',
            ['url' => '/notifikasi']
        );

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi test telah dikirim.',
        ]);
    }
}