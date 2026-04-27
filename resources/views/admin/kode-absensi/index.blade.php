<x-app-layout>
    <x-slot name="header">
        <div>

            <h2 class="font-display text-xl font-semibold text-slate-900">Generate Kode Absensi</h2>
        </div>
    </x-slot>

    <div class="space-y-6">
        <section class="section-card">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h3 class="font-display text-2xl font-semibold text-slate-900">Pilih Kegiatan</h3>
                    <p class="mt-2 text-sm text-slate-500">Pilih satu kegiatan terlebih dulu, lalu generate atau kelola kode aktifnya dari panel yang sama.</p>
                </div>
                <form method="GET" class="grid gap-3 md:grid-cols-[1fr_auto]">
                    <select name="kegiatan_id" class="form-control mt-0">
                        <option value="">Pilih kegiatan</option>
                        @foreach ($kegiatanList as $item)
                            <option value="{{ $item->id }}" @selected($selectedKegiatan === $item->id)>{{ $item->nama_kegiatan }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn-secondary w-full md:w-auto">Tampilkan Panel</button>
                </form>
            </div>

            @if ($currentKegiatan)
                @php
                    $latest = $currentKegiatan->latestCode;
                    $isExpired = $latest?->expired_at?->isPast() ?? false;
                    $statusClass = $isExpired ? 'badge-danger' : ($latest?->is_active ? 'badge-success' : 'badge-warning');
                    $statusLabel = $isExpired ? 'Kedaluwarsa' : ($latest?->is_active ? 'Aktif' : 'Nonaktif');
                @endphp

                <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="rounded-2xl border border-gray-200 bg-slate-50 p-4">
                        <p class="font-display text-xl font-semibold text-slate-900">{{ $currentKegiatan->nama_kegiatan }}</p>
                        <p class="mt-2 text-sm text-slate-500">{{ $currentKegiatan->tanggal->format('d M Y') }} • {{ $currentKegiatan->lokasi }}</p>
                        <p class="mt-4 text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit($currentKegiatan->deskripsi, 180) }}</p>

                        @if ($latest)
                            <div class="mt-4 flex flex-wrap items-center gap-2">
                                <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                                <span class="text-sm text-slate-500">Kode terbaru:</span>
                                <span class="font-display text-lg font-semibold tracking-[0.2em] text-slate-900">{{ $latest->kode }}</span>
                            </div>
                        @else
                            <p class="mt-4 text-sm text-slate-500">Belum ada kode absensi untuk kegiatan ini.</p>
                        @endif
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-white p-4">
                        <p class="text-sm font-semibold text-slate-900">Generate Kode</p>
                        <p class="mt-1 text-sm text-slate-500">Generate baru akan otomatis menonaktifkan kode aktif sebelumnya pada kegiatan yang sama.</p>

                        <form method="POST" action="{{ route('admin.kode-absensi.store', $currentKegiatan) }}" class="mt-4 space-y-3" data-loading-form>
                            @csrf
                            <select name="expired_option" class="form-control mt-0">
                                <option value="10">Expired 10 Menit</option>
                                <option value="15" selected>Expired 15 Menit</option>
                                <option value="30">Expired 30 Menit</option>
                                <option value="custom">Custom</option>
                            </select>
                            <input type="number" name="expired_minutes_custom" min="1" class="form-control mt-0" placeholder="Custom menit">
                            <button type="submit" class="btn-primary w-full md:w-auto" data-loading-label="Generating...">Generate Kode</button>
                        </form>

                        @if ($latest)
                            <div class="mt-5 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-slate-500">QR Kode Terbaru</p>
                                        <div class="mt-3 flex justify-start">
                                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(112)->generate($latest->kode) !!}
                                        </div>
                                    </div>
                                    <div class="flex w-full flex-col gap-2 sm:w-auto">
                                        <form method="POST" action="{{ route('admin.kode-absensi.toggle', $latest) }}" data-loading-form>
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn-secondary w-full md:w-auto" data-loading-label="Memproses...">
                                                {{ $latest->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.kode-absensi.destroy', $latest) }}" onsubmit="return confirm('Yakin hapus kode ini?')" data-loading-form>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger w-full md:w-auto" data-loading-label="Menghapus...">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="empty-state mt-6">
                    Pilih kegiatan terlebih dulu untuk menampilkan panel generate kode dan QR terbaru.
                </div>
            @endif
        </section>

        <section class="table-shell">
            <div class="flex flex-col gap-4 border-b border-slate-100 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div>
                    <h3 class="font-display text-xl font-semibold text-slate-900">Riwayat Kode Absensi</h3>
                    <p class="mt-1 text-sm text-slate-500">Semua kode yang pernah digenerate dapat difilter per kegiatan dan status.</p>
                </div>

                <form method="GET" class="grid w-full gap-3 md:max-w-xl md:grid-cols-3">
                    @if ($selectedKegiatan)
                        <input type="hidden" name="kegiatan_id" value="{{ $selectedKegiatan }}">
                    @endif
                    <select name="status" class="form-control mt-0">
                        <option value="">Semua status</option>
                        <option value="active" @selected($status === 'active')>Aktif</option>
                        <option value="inactive" @selected($status === 'inactive')>Nonaktif</option>
                        <option value="expired" @selected($status === 'expired')>Kedaluwarsa</option>
                    </select>
                    <button type="submit" class="btn-secondary w-full md:w-auto">Filter Status</button>
                    <a href="{{ route('admin.kode-absensi.index', $selectedKegiatan ? ['kegiatan_id' => $selectedKegiatan] : []) }}" class="btn-secondary w-full md:w-auto">Reset</a>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="table-base">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Kegiatan</th>
                            <th>QR</th>
                            <th>Status</th>
                            <th>Durasi</th>
                            <th>Kedaluwarsa</th>
                            <th>Dibuat</th>
                            <th class="w-40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kodeAbsensi as $item)
                            @php
                                $isExpired = $item->expired_at->isPast();
                                $statusClass = $isExpired ? 'badge-danger' : ($item->is_active ? 'badge-success' : 'badge-warning');
                                $statusLabel = $isExpired ? 'Kedaluwarsa' : ($item->is_active ? 'Aktif' : 'Nonaktif');
                            @endphp
                            <tr>
                                <td class="font-display text-lg font-semibold tracking-[0.2em] text-slate-900">{{ $item->kode }}</td>
                                <td>
                                    <p class="font-semibold text-slate-900">{{ $item->kegiatan->nama_kegiatan }}</p>
                                    <p class="mt-1 text-sm text-slate-500">{{ $item->kegiatan->tanggal->format('d M Y') }}</p>
                                </td>
                                <td>
                                    <div class="w-20">
                                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(70)->generate($item->kode) !!}
                                    </div>
                                </td>
                                <td><span class="badge {{ $statusClass }}">{{ $statusLabel }}</span></td>
                                <td>{{ $item->expired_minutes }} menit</td>
                                <td>{{ $item->expired_at->format('d M Y H:i:s') }}</td>
                                <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <div class="flex flex-col gap-2 md:flex-row">
                                        <form method="POST" action="{{ route('admin.kode-absensi.toggle', $item) }}" data-loading-form>
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn-secondary w-full md:w-auto" data-loading-label="Memproses...">
                                                {{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.kode-absensi.destroy', $item) }}" onsubmit="return confirm('Yakin hapus kode ini?')" data-loading-form>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger w-full md:w-auto" data-loading-label="Menghapus...">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-slate-500">Belum ada kode absensi yang sesuai filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{ $kodeAbsensi->links() }}
    </div>
</x-app-layout>
