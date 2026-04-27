<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class KegiatanController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));

        $kegiatan = Kegiatan::query()
            ->withCount('assignedUsers')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder
                        ->where('nama_kegiatan', 'like', "%{$search}%")
                        ->orWhere('lokasi', 'like', "%{$search}%")
                        ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('tanggal')
            ->paginate(10)
            ->withQueryString();

        return view('admin.kegiatan.index', [
            'kegiatan' => $kegiatan,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.kegiatan.create', [
            'anggotaList' => $this->anggotaList(),
        ]);
    }

    public function store(Request $request, ActivityLogService $activityLogService): RedirectResponse
    {
        $validated = $this->validatePayload($request);
        $assignedUserIds = $validated['assigned_user_ids'] ?? [];
        unset($validated['assigned_user_ids']);

        DB::transaction(function () use ($validated, $assignedUserIds, $request, $activityLogService) {
            $kegiatan = Kegiatan::query()->create($validated);
            $kegiatan->assignedUsers()->sync($assignedUserIds);

            $activityLogService->log(
                $request->user(),
                'create_kegiatan',
                "Membuat kegiatan {$kegiatan->nama_kegiatan} pada {$kegiatan->tanggal->format('Y-m-d')}."
            );

            $activityLogService->log(
                $request->user(),
                'update_assignment',
                "Memperbarui assignment kegiatan {$kegiatan->nama_kegiatan} dengan total ".count($assignedUserIds).' anggota.'
            );
        });

        return redirect()
            ->route('admin.kegiatan.index')
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function edit(Kegiatan $kegiatan): View
    {
        return view('admin.kegiatan.edit', [
            'kegiatan' => $kegiatan->load('assignedUsers'),
            'anggotaList' => $this->anggotaList(),
        ]);
    }

    public function update(Request $request, Kegiatan $kegiatan, ActivityLogService $activityLogService): RedirectResponse
    {
        $validated = $this->validatePayload($request);
        $assignedUserIds = $validated['assigned_user_ids'] ?? [];
        unset($validated['assigned_user_ids']);

        DB::transaction(function () use ($validated, $assignedUserIds, $kegiatan, $request, $activityLogService) {
            $originalAssigned = $kegiatan->assignedUsers()->pluck('users.id')->sort()->values()->all();

            $kegiatan->update($validated);
            $kegiatan->assignedUsers()->sync($assignedUserIds);

            if ($originalAssigned !== collect($assignedUserIds)->sort()->values()->all()) {
                $activityLogService->log(
                    $request->user(),
                    'update_assignment',
                    "Memperbarui assignment kegiatan {$kegiatan->nama_kegiatan} dengan total ".count($assignedUserIds).' anggota.'
                );
            }
        });

        return redirect()
            ->route('admin.kegiatan.index')
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan): RedirectResponse
    {
        $kegiatan->delete();

        return redirect()
            ->route('admin.kegiatan.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatePayload(Request $request): array
    {
        return $request->validate([
            'nama_kegiatan' => ['required', 'string', 'max:255'],
            'tanggal' => ['required', 'date'],
            'lokasi' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'assigned_user_ids' => ['nullable', 'array'],
            'assigned_user_ids.*' => [
                'integer',
                Rule::exists('users', 'id')->where(fn ( $query) => $query->where('role', User::ROLE_ANGGOTA)),
            ],
        ]);
    }

    private function anggotaList()
    {
        return User::query()
            ->where('role', User::ROLE_ANGGOTA)
            ->with('anggota:id,user_id,npm')
            ->orderBy('name')
            ->get();
    }
}
