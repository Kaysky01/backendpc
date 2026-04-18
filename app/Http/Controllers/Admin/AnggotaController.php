<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AnggotaController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));

        $anggotas = User::query()
            ->where('role', User::ROLE_ANGGOTA)
            ->with('anggota')
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(function (Builder $builder) use ($search) {
                    $builder
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('anggota', function (Builder $anggotaQuery) use ($search) {
                            $anggotaQuery
                                ->where('npm', 'like', "%{$search}%")
                                ->orWhere('prodi', 'like', "%{$search}%")
                                ->orWhere('angkatan', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.anggota.index', [
            'anggotas' => $anggotas,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.anggota.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePayload($request);

        DB::transaction(function () use ($validated) {
            $user = User::query()->create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'role' => User::ROLE_ANGGOTA,
            ]);

            $user->anggota()->create([
                'npm' => $validated['npm'],
                'prodi' => $validated['prodi'],
                'angkatan' => $validated['angkatan'],
            ]);
        });

        return redirect()
            ->route('admin.anggota.index')
            ->with('success', 'Data anggota berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        $this->assertAnggota($user);
        $user->load('anggota');

        return view('admin.anggota.edit', ['anggota' => $user]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->assertAnggota($user);
        $user->load('anggota');

        $validated = $this->validatePayload($request, $user);

        DB::transaction(function () use ($user, $validated) {
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            if (! empty($validated['password'])) {
                $userData['password'] = $validated['password'];
            }

            $user->update($userData);
            $user->anggota()->update([
                'npm' => $validated['npm'],
                'prodi' => $validated['prodi'],
                'angkatan' => $validated['angkatan'],
            ]);
        });

        return redirect()
            ->route('admin.anggota.index')
            ->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->assertAnggota($user);
        $user->delete();

        return redirect()
            ->route('admin.anggota.index')
            ->with('success', 'Data anggota berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatePayload(Request $request, ?User $user = null): array
    {
        $anggotaId = $user?->anggota?->id;

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user?->id),
            ],
            'password' => [
                $user ? 'nullable' : 'required',
                'confirmed',
                Password::defaults(),
            ],
            'npm' => [
                'required',
                'string',
                'max:30',
                Rule::unique('anggota', 'npm')->ignore($anggotaId),
            ],
            'prodi' => ['required', 'string', 'max:255'],
            'angkatan' => ['required', 'string', 'max:10'],
        ]);
    }

    private function assertAnggota(User $user): void
    {
        abort_unless($user->isAnggota(), 404);
    }
}
