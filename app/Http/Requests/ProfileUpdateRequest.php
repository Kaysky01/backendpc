<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $anggotaId = $this->user()->anggota?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'password' => ['nullable', 'confirmed', Password::min(6)],
            'npm' => ['nullable', 'string', 'max:30', Rule::unique('anggota', 'npm')->ignore($anggotaId)],
            'prodi' => ['nullable', 'string', 'max:255'],
            'angkatan' => ['nullable', 'string', 'max:10'],
            'divisi' => ['nullable', 'string', 'max:255'],
            'role_detail' => ['nullable', 'string', 'max:255'],
        ];
    }
}
