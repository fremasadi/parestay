<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    public function sendMessage(string $target, string $message): bool
    {
        $token = config('services.fonnte.token');

        if (!$token) {
            Log::warning('Fonnte token belum dikonfigurasi.');
            return false;
        }

        try {
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

            return true;
        } catch (\Throwable $e) {
            Log::error('Error saat mengirim pesan Fonnte', [
                'target' => $target,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
