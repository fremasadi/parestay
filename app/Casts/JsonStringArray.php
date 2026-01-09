<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class JsonStringArray implements CastsAttributes
{
    /**
     * Cast dari database (string) ke array PHP
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): array
    {
        if (is_null($value)) {
            return [];
        }

        // Jika sudah array, langsung return
        if (is_array($value)) {
            return $value;
        }

        // Decode pertama
        $decoded = json_decode($value, true);

        // Jika hasil decode masih string (double-encoded), decode lagi
        if (is_string($decoded)) {
            $decoded = json_decode($decoded, true);
        }

        return $decoded ?? [];
    }

    /**
     * Cast dari array PHP ke string JSON untuk disimpan ke database
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        if (is_null($value)) {
            return '[]';
        }

        // Jika input sudah string JSON, decode dulu jadi array
        if (is_string($value)) {
            $value = json_decode($value, true) ?? [];
        }

        // Encode sekali saja
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}