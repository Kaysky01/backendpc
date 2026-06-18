<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\ImportHistory;
use App\Models\Kegiatan;
use App\Models\User;
use App\Services\AttendanceStatusService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AbsensiController extends Controller
{
    public function index(Request $request): View
    {
        $filters = [
            'kegiatan_id' => $request->integer('kegiatan_id'),
            'user_id' => $request->integer('user_id'),
            'tanggal' => (string) $request->string('tanggal'),
            'divisi' => trim((string) $request->string('divisi')),
        ];

        $absensi = Absensi::query()
            ->with(['user.anggota', 'kegiatan'])
            ->when($filters['kegiatan_id'] > 0, fn ($query) => $query->where('kegiatan_id', $filters['kegiatan_id']))
            ->when($filters['user_id'] > 0, fn ($query) => $query->where('user_id', $filters['user_id']))
            ->when($filters['divisi'] !== '', function ($query) use ($filters) {
                $query->whereHas('user', fn ($userQuery) => $userQuery->where('divisi', $filters['divisi']));
            })
            ->when($filters['tanggal'] !== '', function ($query) use ($filters) {
                $query->whereHas('kegiatan', function ($kegiatanQuery) use ($filters) {
                    $kegiatanQuery->whereDate('tanggal', $filters['tanggal']);
                });
            })
            ->latest('waktu_absen')
            ->paginate(12)
            ->withQueryString();

        return view('admin.absensi.index', [
            'absensi' => $absensi,
            'anggotaList' => User::query()->where('role', User::ROLE_ANGGOTA)->with('anggota')->orderBy('name')->get(),
            'kegiatanList' => Kegiatan::query()->orderByDesc('tanggal')->get(),
            'divisiList' => User::query()
                ->where('role', User::ROLE_ANGGOTA)
                ->whereNotNull('divisi')
                ->where('divisi', '!=', '')
                ->distinct()
                ->orderBy('divisi')
                ->pluck('divisi'),
            'filters' => $filters,
            'importHistories' => ImportHistory::with('user')->latest()->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.absensi.create', $this->formData());
    }

    public function store(Request $request, AttendanceStatusService $attendanceStatusService): RedirectResponse
    {
        $validated = $this->validatePayload($request, null, $attendanceStatusService);
        Absensi::query()->create($validated);

        return redirect()
            ->route('admin.absensi.index')
            ->with('success', 'Absensi manual berhasil ditambahkan.');
    }

    public function edit(Absensi $absensi): View
    {
        return view('admin.absensi.edit', array_merge($this->formData(), [
            'absensiItem' => $absensi->load(['user.anggota', 'kegiatan']),
        ]));
    }

    public function update(Request $request, Absensi $absensi, AttendanceStatusService $attendanceStatusService): RedirectResponse
    {
        $validated = $this->validatePayload($request, $absensi, $attendanceStatusService);
        $absensi->update($validated);

        return redirect()
            ->route('admin.absensi.index')
            ->with('success', 'Data absensi berhasil diperbarui.');
    }

    /**
     * @return array<string, mixed>
     */
    private function formData(): array
    {
        return [
            'anggotaList' => User::query()
                ->where('role', User::ROLE_ANGGOTA)
                ->with('anggota')
                ->orderBy('name')
                ->get(),
            'kegiatanList' => Kegiatan::query()->orderByDesc('tanggal')->get(),
            'statusOptions' => Absensi::manualStatusOptions(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function validatePayload(
        Request $request,
        ?Absensi $absensi = null,
        ?AttendanceStatusService $attendanceStatusService = null
    ): array {
        $validator = Validator::make($request->all(), [
            'user_id' => [
                'required',
                Rule::exists('users', 'id')->where('role', User::ROLE_ANGGOTA),
            ],
            'kegiatan_id' => ['required', 'exists:kegiatan,id'],
            'status' => ['required', Rule::in(Absensi::manualStatusOptions())],
            'waktu_absen' => ['nullable', 'date'],
        ]);

        $validator->after(function ($validator) use ($request, $absensi, $attendanceStatusService) {
            $exists = Absensi::query()
                ->where('user_id', $request->input('user_id'))
                ->where('kegiatan_id', $request->input('kegiatan_id'))
                ->when($absensi, fn ($query) => $query->where('id', '!=', $absensi->id))
                ->exists();

            if ($exists) {
                $validator->errors()->add('user_id', 'Anggota ini sudah memiliki data absensi untuk kegiatan yang dipilih.');
            }

            $selectedUserId = (int) $request->input('user_id');
            $selectedKegiatanId = (int) $request->input('kegiatan_id');

            if ($selectedUserId < 1 || $selectedKegiatanId < 1) {
                return;
            }

            $samePairAsExisting = $absensi
                && $absensi->user_id === $selectedUserId
                && $absensi->kegiatan_id === $selectedKegiatanId;

            if ($samePairAsExisting) {
                return;
            }

            $user = User::query()->find($selectedUserId);
            $kegiatan = Kegiatan::query()->with('assignedUsers')->find($selectedKegiatanId);

            if ($user && $kegiatan && $attendanceStatusService && ! $attendanceStatusService->canAttend($user, $kegiatan)) {
                $validator->errors()->add('user_id', 'Tidak ditugaskan.');
            }
        });

        $validated = $validator->validate();
        $validated['waktu_absen'] = $validated['waktu_absen'] ?? now();

        return $validated;
    }
}
