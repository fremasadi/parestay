<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    public function sendMessage(string $target, string $message): bool
    {
        $token = config('services.fonnte.token');
        $target = $this->normalizeTarget($target);

        if (!$token) {
            Log::warning('Fonnte token belum dikonfigurasi.');
            return false;
        }

        if ($target === '') {
            Log::warning('Target Fonnte kosong atau tidak valid.');
            return false;
        }

        $fingerprint = sha1($target . '|' . trim($message));
        $sentCacheKey = "fonnte:sent:{$fingerprint}";
        $lockKey = "fonnte:lock:{$fingerprint}";

        if (Cache::has($sentCacheKey)) {
            Log::info('Duplikasi notifikasi Fonnte ditekan', [
                'target' => $target,
                'fingerprint' => $fingerprint,
            ]);

            return true;
        }

        try {
            return Cache::lock($lockKey, 15)->block(3, function () use ($fingerprint, $message, $sentCacheKey, $target, $token) {
                if (Cache::has($sentCacheKey)) {
                    Log::info('Duplikasi notifikasi Fonnte ditekan setelah lock', [
                        'target' => $target,
                        'fingerprint' => $fingerprint,
                    ]);

                    return true;
                }

                $response = Http::withHeaders([
                    'Authorization' => $token,
                ])->asForm()->post('https://api.fonnte.com/send', [
                    'target' => $target,
                    'message' => $message,
                    'countryCode' => '62',
                ]);

                if (!$response->successful()) {
                    Log::warning('Gagal mengirim pesan Fonnte', [
                        'target' => $target,
                        'status' => $response->status(),
                        'response' => $response->body(),
                    ]);

                    return false;
                }

                $payload = $response->json();

                if (($payload['status'] ?? false) !== true) {
                    Log::warning('Fonnte mengembalikan status gagal', [
                        'target' => $target,
                        'response' => $payload,
                    ]);

                    return false;
                }

                Cache::put($sentCacheKey, now()->toIso8601String(), now()->addMinutes(10));

                return true;
            });
        } catch (\Throwable $e) {
            Log::error('Error saat mengirim pesan Fonnte', [
                'target' => $target,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    private function normalizeTarget(string $target): string
    {
        return preg_replace('/\D+/', '', $target) ?? '';
    }
}
