<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));

        $kegiatan = Kegiatan::query()
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
        return view('admin.kegiatan.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePayload($request);
        Kegiatan::query()->create($validated);

        return redirect()
            ->route('admin.kegiatan.index')
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function edit(Kegiatan $kegiatan): View
    {
        return view('admin.kegiatan.edit', compact('kegiatan'));
    }

    public function update(Request $request, Kegiatan $kegiatan): RedirectResponse
    {
        $validated = $this->validatePayload($request);
        $kegiatan->update($validated);

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
        ]);
    }
}
