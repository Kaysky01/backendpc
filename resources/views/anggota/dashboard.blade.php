<x-app-layout>
    <x-slot name="header">
        <div>
        
            <h2 class="font-display text-xl font-semibold text-slate-900">Dashboard </h2>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="grid gap-5 md:grid-cols-3">
            <div class="stats-card">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm font-medium text-slate-500">Total Hadir</p>
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-sky-100 text-sky-600">
                        <x-icon name="check-circle" class="h-5 w-5" />
                    </span>
                </div>
                <p class="stat-value">{{ $stats['hadir'] }}</p>
                <p class="mt-3 text-sm text-slate-500">Absensi berhasil tervalidasi dengan kode aktif.</p>
            </div>
            <div class="stats-card">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm font-medium text-slate-500">Total Izin</p>
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                        <x-icon name="document" class="h-5 w-5" />
                    </span>
                </div>
                <p class="stat-value">{{ $stats['izin'] }}</p>
                <p class="mt-3 text-sm text-slate-500">Status izin yang dicatat oleh admin.</p>
            </div>
            <div class="stats-card">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm font-medium text-slate-500">Total Alfa</p>
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-red-100 text-red-600">
                        <x-icon name="exclamation-triangle" class="h-5 w-5" />
                    </span>
                </div>
                <p class="stat-value">{{ $stats['alfa'] }}</p>
                <p class="mt-3 text-sm text-slate-500">Kegiatan yang terlewat atau ditandai alfa.</p>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
            <section class="section-card">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Quick Actions</p>
                        <h3 class="mt-1 font-display text-2xl font-semibold text-slate-900">Langkah Berikutnya</h3>
                    </div>
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <a href="{{ route('anggota.kegiatan.index') }}" class="info-tile rounded-2xl">
                        <p class="font-display text-xl font-semibold text-slate-900">Lihat Kegiatan</p>
                        <p class="mt-2 text-sm text-slate-500">Periksa daftar kegiatan dan status sudah/belum absen.</p>
                    </a>
                    <a href="{{ route('anggota.absensi.create') }}" class="info-tile rounded-2xl">
                        <p class="font-display text-xl font-semibold text-slate-900">Input Kode</p>
                        <p class="mt-2 text-sm text-slate-500">Masukkan kode absensi yang aktif untuk mencatat kehadiran Anda.</p>
                    </a>
                    <a href="{{ route('anggota.riwayat.index') }}" class="info-tile rounded-2xl md:col-span-2">
                        <p class="font-display text-xl font-semibold text-slate-900">Riwayat Absensi</p>
                        <p class="mt-2 text-sm text-slate-500">Cek status hadir, izin, alfa, serta waktu absen dengan detail timestamp.</p>
                    </a>
                </div>
            </section>

            <section class="section-card">
                <div>
                    <p class="text-sm font-medium text-slate-500">Agenda</p>
                    <h3 class="mt-1 font-display text-2xl font-semibold text-slate-900">Kegiatan Mendatang</h3>
                </div>

                <div class="mt-6 space-y-4">
                    @forelse ($upcomingKegiatan as $item)
                        <div class="rounded-2xl border border-gray-200 bg-slate-50 p-4">
                            <p class="font-semibold text-slate-900">{{ $item->nama_kegiatan }}</p>
                            <p class="mt-2 text-sm text-slate-500">{{ $item->tanggal->format('d M Y') }} • {{ $item->lokasi }}</p>
                            <p class="mt-3 text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit($item->deskripsi, 120) }}</p>
                        </div>
                    @empty
                        <div class="empty-state">
                            Belum ada kegiatan mendatang.
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
