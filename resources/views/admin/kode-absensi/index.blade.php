<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-slate-500">Admin Panel</p>
            <h2 class="font-display text-3xl font-semibold text-slate-900">Generate Kode Absensi</h2>
        </div>
    </x-slot>

    <div class="space-y-6">
        <section class="section-card">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="font-display text-2xl font-semibold text-slate-900">Kegiatan Siap Generate</h3>
                    <p class="mt-2 text-sm text-slate-500">Setiap generate baru akan menonaktifkan kode aktif sebelumnya pada kegiatan yang sama.</p>
                </div>
                <form method="GET" class="grid gap-3 md:grid-cols-2">
                    <select name="kegiatan_id" class="form-control mt-0">
                        <option value="">Semua kegiatan</option>
                        @foreach ($kegiatanList as $item)
    
                            <option value="{{ $item->id }}" @selected($selectedKegiatan === $item->id)>{{ $item->nama_kegiatan }}</option>
                        @endforeach
                    </select>

                    <select name="status" class="form-control mt-0">
                        <option value="">Semua status</option>
                        <option value="active" @selected($status === 'active')>Aktif</option>
                        <option value="inactive" @selected($status === 'inactive')>Nonaktif</option>
                        <option value="expired" @selected($status === 'expired')>Kedaluwarsa</option>
                    </select>

                    <button type="submit" class="btn-secondary md:col-span-2">Terapkan Filter</button>
                </form>
            </div>

            <div class="mt-6 grid gap-4 xl:grid-cols-2">
                @foreach ($kegiatanList as $item)
    @if ($selectedKegiatan && $item->id != $selectedKegiatan)
        @continue
    @endif
                    @php
                        $latest = $item->latestCode;
                        $isExpired = $latest?->expired_at?->isPast() ?? false;
                    @endphp
                    <div class="rounded-2xl border border-gray-200 bg-slate-50 p-5">
                        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                            <div>
                                <p class="font-display text-xl font-semibold text-slate-900">{{ $item->nama_kegiatan }}</p>
                                <p class="mt-2 text-sm text-slate-500">{{ $item->tanggal->format('d M Y') }} • {{ $item->lokasi }}</p>
                                @if ($latest)
                                    <div class="mt-4 flex flex-wrap items-center gap-2">
                                        <span class="badge {{ $isExpired ? 'badge-danger' : ($latest->is_active ? 'badge-success' : 'badge-warning') }}">
                                            {{ $isExpired ? 'Kedaluwarsa' : ($latest->is_active ? 'Aktif' : 'Nonaktif') }}
                                        </span>
                                        <span class="text-sm text-slate-500">
                                            Kode terbaru: <strong class="text-slate-900">{{ $latest->kode }}</strong>
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <form method="POST" action="{{ route('admin.kode-absensi.store', $item) }}">
                                @csrf
                                <div class="space-y-3">
                                    <select name="expired_option" class="form-control mt-0">
                                        <option value="10">Expired 10 Menit</option>
                                        <option value="15" selected>Expired 15 Menit</option>
                                        <option value="30">Expired 30 Menit</option>
                                        <option value="custom">Custom</option>
                                    </select>
                                    <input type="number" name="expired_minutes_custom" min="1" class="form-control mt-0" placeholder="Custom menit">
                                    <button type="submit" class="btn-primary w-full">Generate 6 Karakter</button>
                                </div>
                            </form>
                        </div>

                        @if ($latest)
                            <div class="mt-4 rounded-2xl border border-gray-200 bg-white p-4">
                                <p class="text-sm font-medium text-slate-500">QR Kode Aktif</p>
                                <div class="mt-3 flex justify-center">
                                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(110)->generate($latest->kode) !!}
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>

        <section class="table-shell">
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
                                    <form method="POST" action="{{ route('admin.kode-absensi.toggle', $item) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-secondary !px-4 !py-2">
                                            {{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
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
