<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];

        if ($this->user() && $this->user()->role === 'penyewa') {
            $rules['no_hp'] = ['required', 'string', 'max:20'];
            $rules['no_ktp'] = ['required', 'string', 'max:20'];
            $rules['pekerjaan'] = ['required', 'string', 'max:255'];
            $rules['alamat'] = ['required', 'string'];
            $rules['foto_ktp'] = ['nullable', 'image', 'max:2048'];
        }

        return $rules;
    }
}
