<x-app-layout>
    <x-slot name="header">
        <div>
  
            <h2 class="font-display text-xl font-semibold text-slate-900">Detail Kegiatan</h2>
        </div>
    </x-slot>

    <div class="space-y-6">
        <section class="section-card">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h3 class="font-display text-2xl font-semibold text-slate-900">{{ $kegiatan->nama_kegiatan }}</h3>
                    <p class="mt-2 text-sm text-slate-500">{{ $kegiatan->tanggal->format('d M Y') }} • {{ $kegiatan->lokasi }}</p>
                    <p class="mt-4 text-sm leading-6 text-slate-600">{{ $kegiatan->deskripsi }}</p>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-slate-50 p-4">
                    @php
                        $statusClass = match ($status) {
                            'hadir' => 'badge-success',
                            'izin' => 'badge-warning',
                            'tidak_ditugaskan', 'belum_absen' => 'badge-neutral',
                            default => 'badge-danger',
                        };
                    @endphp
                    <p class="text-sm font-medium text-slate-500">Status Anda</p>
                    <div class="mt-3">
                        <span class="badge {{ $statusClass }}">{{ str($status)->replace('_', ' ')->title() }}</span>
                    </div>
                    @if ($attendance?->waktu_absen)
                        <p class="mt-3 text-sm text-slate-500">Waktu absen: {{ $attendance->waktu_absen->format('d M Y H:i:s') }}</p>
                    @endif
                </div>
            </div>
        </section>

        <section class="section-card">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="font-display text-2xl font-semibold text-slate-900">Aksi Kehadiran</h3>
                    <p class="mt-2 text-sm text-slate-500">Gunakan halaman input kode jika Anda ditugaskan dan kode masih aktif.</p>
                </div>
                <a href="{{ route('anggota.absensi.create') }}" class="btn-primary {{ $canAttend ? '' : 'pointer-events-none opacity-60' }}">
                    Input Kode Absensi
                </a>
            </div>

            @if (! $canAttend)
                <div class="mt-5 rounded-2xl border border-gray-200 bg-slate-50 p-4 text-sm text-slate-600">
                    Anda dapat melihat detail kegiatan ini, tetapi tidak bisa melakukan absensi karena status Anda tidak ditugaskan.
                </div>
            @elseif ($kegiatan->latestCode)
                <div class="mt-5 rounded-2xl border border-gray-200 bg-slate-50 p-4 text-sm text-slate-600">
                    Kode absensi terbaru tersedia dengan masa aktif {{ $kegiatan->latestCode->expired_minutes }} menit sejak dibuat admin.
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
