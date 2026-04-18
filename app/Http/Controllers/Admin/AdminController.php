<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));

        $admins = User::query()
            ->where('role', User::ROLE_ADMIN)
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(function (Builder $builder) use ($search) {
                    $builder
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('is_super_admin')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.admins.index', [
            'admins' => $admins,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.admins.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePayload($request);

        User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => User::ROLE_ADMIN,
            'is_super_admin' => (bool) ($validated['is_super_admin'] ?? false),
        ]);

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Admin baru berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        $this->assertAdmin($user);

        return view('admin.admins.edit', ['admin' => $user]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->assertAdmin($user);

        $validated = $this->validatePayload($request, $user);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'is_super_admin' => (bool) ($validated['is_super_admin'] ?? false),
        ];

        if (! empty($validated['password'])) {
            $data['password'] = $validated['password'];
        }

        if ($user->is_super_admin && ! $data['is_super_admin'] && User::query()->where('is_super_admin', true)->count() === 1) {
            return back()
                ->withErrors(['is_super_admin' => 'Setidaknya harus ada satu super admin aktif.'])
                ->withInput();
        }

        $user->update($data);

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Data admin berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $this->assertAdmin($user);

        if ($request->user()->is($user)) {
            return back()->with('error', 'Anda tidak dapat menghapus akun yang sedang digunakan.');
        }

        if ($user->is_super_admin && User::query()->where('is_super_admin', true)->count() === 1) {
            return back()->with('error', 'Super admin terakhir tidak dapat dihapus.');
        }

        $user->delete();

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Data admin berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatePayload(Request $request, ?User $user = null): array
    {
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
            'is_super_admin' => ['nullable', 'boolean'],
        ]);
    }

    private function assertAdmin(User $user): void
    {
        abort_unless($user->isAdmin(), 404);
    }
}
