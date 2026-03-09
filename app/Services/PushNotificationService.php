<?php

namespace App\Services;

use App\Models\PushSubscription;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PushNotificationService
{
    /**
     * Kirim push notification ke semua subscription aktif satu NIM
     */
    public function sendToNim(string $nim, string $judul, string $pesan, array $data = []): void
    {
        $subscriptions = PushSubscription::where('nim', $nim)->get();
        foreach ($subscriptions as $sub) {
            $this->send($sub, $judul, $pesan, $data);
        }
    }

    /**
     * Kirim push notification ke semua mahasiswa (broadcast)
     */
    public function broadcast(string $judul, string $pesan, array $data = []): void
    {
        $subscriptions = PushSubscription::all();
        foreach ($subscriptions as $sub) {
            $this->send($sub, $judul, $pesan, $data);
        }
    }

    /**
     * Kirim ke satu subscription
     */
    private function send(PushSubscription $sub, string $judul, string $pesan, array $data = []): void
    {
        try {
            $vapidPublic  = config('app.vapid_public_key');
            $vapidPrivate = config('app.vapid_private_key');
            $vapidSubject = config('app.url');

            if (!$vapidPublic || !$vapidPrivate) {
                Log::warning('VAPID keys belum dikonfigurasi di .env');
                return;
            }

            $payload = json_encode([
                'title'   => $judul,
                'body'    => $pesan,
                'icon'    => '/logo_kecil.png',
                'badge'   => '/logo_kecil.png',
                'data'    => array_merge(['url' => '/notifikasi'], $data),
                'tag'     => 'siakad-notif-' . time(),
                'vibrate' => [200, 100, 200],
            ]);

            // Gunakan web-push via minishlink/web-push jika tersedia
            // Fallback: kirim manual via HTTP (untuk demo tanpa install package)
            if (class_exists('\Minishlink\WebPush\WebPush')) {
                $this->sendViaWebPush($sub, $payload, $vapidPublic, $vapidPrivate, $vapidSubject);
            } else {
                // Fallback simpan ke log untuk demo
                Log::info("PUSH NOTIF ke {$sub->nim}: {$judul} - {$pesan}");
                Log::info("Endpoint: {$sub->endpoint}");
                Log::info("Payload: {$payload}");
            }

        } catch (\Exception $e) {
            Log::error('Push notification gagal: ' . $e->getMessage());
        }
    }

    private function sendViaWebPush($sub, $payload, $vapidPublic, $vapidPrivate, $vapidSubject): void
    {
        $auth = [
            'VAPID' => [
                'subject'    => $vapidSubject,
                'publicKey'  => $vapidPublic,
                'privateKey' => $vapidPrivate,
            ],
        ];

        $webPush = new \Minishlink\WebPush\WebPush($auth);

        $subscription = \Minishlink\WebPush\Subscription::create([
            'endpoint' => $sub->endpoint,
            'keys'     => [
                'p256dh' => $sub->public_key,
                'auth'   => $sub->auth_token,
            ],
        ]);

        $webPush->queueNotification($subscription, $payload);

        foreach ($webPush->flush() as $report) {
            if (!$report->isSuccess()) {
                Log::warning("Push gagal ke {$sub->endpoint}: " . $report->getReason());
                // Hapus subscription yang sudah tidak valid
                if ($report->isSubscriptionExpired()) {
                    $sub->delete();
                }
            }
        }
    }
}