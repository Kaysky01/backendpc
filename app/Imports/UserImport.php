<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToCollection, WithHeadingRow
{
    private const DEFAULT_PASSWORD = 'password123';

    public int $imported = 0;

    public int $skipped = 0;

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $name = trim((string) ($row['name'] ?? ''));
            $email = strtolower(trim((string) ($row['email'] ?? '')));
            $password = trim((string) ($row['password'] ?? ''));
            $divisi = trim((string) ($row['divisi'] ?? ''));
            $roleDetail = trim((string) ($row['role_detail'] ?? ''));

            if ($name === '' || $email === '') {
                $this->skipped++;

                continue;
            }

            $user = User::query()->where('email', $email)->first();

            if ($user) {
                $user->update([
                    'divisi' => $divisi !== '' ? $divisi : null,
                    'role_detail' => $roleDetail !== '' ? $roleDetail : null,
                ]);

                $this->imported++;

                continue;
            }

            User::query()->create([
                'name' => $name,
                'email' => $email,
                'password' => $password !== '' ? $password : self::DEFAULT_PASSWORD,
                'role' => User::ROLE_ANGGOTA,
                'divisi' => $divisi !== '' ? $divisi : null,
                'role_detail' => $roleDetail !== '' ? $roleDetail : null,
            ]);

            $this->imported++;
        }
    }
}
