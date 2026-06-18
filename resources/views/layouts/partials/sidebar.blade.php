@php
    $user = auth()->user();

    $links = $user->isAdmin()
        ? [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'pattern' => 'admin.dashboard', 'hint' => 'Statistik', 'icon' => 'home'],
            ['label' => 'Data Anggota', 'route' => 'admin.anggota.index', 'pattern' => 'admin.anggota.*', 'hint' => ' Kelola anggota', 'icon' => 'users'],
            ['label' => 'Data Admin', 'route' => 'admin.admins.index', 'pattern' => 'admin.admins.*', 'hint' => 'Super admin', 'show' => $user->isSuperAdmin(), 'icon' => 'shield-check'],
            ['label' => 'Kegiatan', 'route' => 'admin.kegiatan.index', 'pattern' => 'admin.kegiatan.*', 'hint' => 'Kelola event', 'icon' => 'calendar'],
            ['label' => 'Kode Absensi', 'route' => 'admin.kode-absensi.index', 'pattern' => 'admin.kode-absensi.*', 'hint' => 'Kode aktif', 'icon' => 'key'],
            ['label' => 'Monitoring', 'route' => 'admin.absensi.index', 'pattern' => 'admin.absensi.*', 'hint' => 'Data hadir', 'icon' => 'clipboard'],
            ['label' => 'Rekap PDF', 'route' => 'admin.reports.index', 'pattern' => 'admin.reports.*', 'hint' => 'Laporan', 'icon' => 'document'],
            ['label' => 'Riwayat Aktivitas', 'route' => 'admin.activity-log.index', 'pattern' => 'admin.activity-log.*', 'hint' => 'Semua log', 'icon' => 'clock'],
        ]
        : [
            ['label' => 'Dashboard', 'route' => 'anggota.dashboard', 'pattern' => 'anggota.dashboard', 'hint' => 'Ringkasan', 'icon' => 'home'],
            ['label' => 'Daftar Kegiatan', 'route' => 'anggota.kegiatan.index', 'pattern' => 'anggota.kegiatan.*', 'hint' => 'Jadwal', 'icon' => 'calendar'],
            ['label' => 'Input Kode', 'route' => 'anggota.absensi.create', 'pattern' => 'anggota.absensi.*', 'hint' => 'Isi absensi', 'icon' => 'key'],
            ['label' => 'Riwayat', 'route' => 'anggota.riwayat.index', 'pattern' => 'anggota.riwayat.*', 'hint' => 'Timestamp', 'icon' => 'clock'],
        ];
@endphp

<div class="flex h-full flex-col">
   

    <nav class="space-y-1">
        @foreach ($links as $link)
            @continue(isset($link['show']) && ! $link['show'])

            @php
                $active = request()->routeIs($link['pattern']);
            @endphp

            <a
                href="{{ route($link['route']) }}"
                class="sidebar-link {{ $active ? 'sidebar-link-active' : '' }}"
            >
                <span class="flex items-center gap-3">
                    <span class="sidebar-icon">
                        <x-icon :name="$link['icon']" class="h-5 w-5" />
                    </span>
                    <span>
                        <span class="block">{{ $link['label'] }}</span>
                        <span class="mt-0.5 block text-xs font-normal text-slate-400 {{ $active ? '!text-sky-500' : '' }}">
                            {{ $link['hint'] }}
                        </span>
                    </span>
                </span>
            </a>
        @endforeach
    </nav>


    <p class="mt-auto border-dashed text-center text-xs text-slate-400">
        Versi 1.1  &copy; {{ date('Y') }} Nata.
        All rights reserved.
    </p>
</div>
