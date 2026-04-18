@php
    $user = auth()->user();

    $links = $user->isAdmin()
        ? [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'pattern' => 'admin.dashboard', 'hint' => 'Statistik', 'icon' => 'home'],
            ['label' => 'Data Anggota', 'route' => 'admin.anggota.index', 'pattern' => 'admin.anggota.*', 'hint' => 'CRUD anggota', 'icon' => 'users'],
            ['label' => 'Data Admin', 'route' => 'admin.admins.index', 'pattern' => 'admin.admins.*', 'hint' => 'Super admin', 'show' => $user->isSuperAdmin(), 'icon' => 'shield-check'],
            ['label' => 'Kegiatan', 'route' => 'admin.kegiatan.index', 'pattern' => 'admin.kegiatan.*', 'hint' => 'Kelola event', 'icon' => 'calendar'],
            ['label' => 'Kode Absensi', 'route' => 'admin.kode-absensi.index', 'pattern' => 'admin.kode-absensi.*', 'hint' => 'Kode aktif', 'icon' => 'key'],
            ['label' => 'Monitoring', 'route' => 'admin.absensi.index', 'pattern' => 'admin.absensi.*', 'hint' => 'Data hadir', 'icon' => 'clipboard'],
            ['label' => 'Rekap PDF', 'route' => 'admin.reports.index', 'pattern' => 'admin.reports.*', 'hint' => 'Laporan', 'icon' => 'document'],
        ]
        : [
            ['label' => 'Dashboard', 'route' => 'anggota.dashboard', 'pattern' => 'anggota.dashboard', 'hint' => 'Ringkasan', 'icon' => 'home'],
            ['label' => 'Daftar Kegiatan', 'route' => 'anggota.kegiatan.index', 'pattern' => 'anggota.kegiatan.*', 'hint' => 'Jadwal', 'icon' => 'calendar'],
            ['label' => 'Input Kode', 'route' => 'anggota.absensi.create', 'pattern' => 'anggota.absensi.*', 'hint' => 'Isi absensi', 'icon' => 'key'],
            ['label' => 'Riwayat', 'route' => 'anggota.riwayat.index', 'pattern' => 'anggota.riwayat.*', 'hint' => 'Timestamp', 'icon' => 'clock'],
        ];
@endphp

<div class="flex h-full flex-col">
    <x-card class="mb-6 bg-slate-50">
        <div class="flex items-start gap-3">
            <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-100 text-base font-semibold text-sky-600">
                {{ \Illuminate\Support\Str::of($user->name)->explode(' ')->take(2)->map(fn ($item) => \Illuminate\Support\Str::substr($item, 0, 1))->implode('') }}
            </span>
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-sky-600">
                    {{ $user->isAdmin() ? 'Admin Workspace' : 'Anggota Workspace' }}
                </p>
                <h2 class="mt-1 font-display text-lg font-semibold text-slate-900">{{ $user->name }}</h2>
                <p class="mt-1 text-sm text-slate-500">{{ $user->email }}</p>
                <div class="mt-4 flex flex-wrap gap-2">
                    <x-badge variant="success">{{ ucfirst($user->role) }}</x-badge>
                    @if ($user->isSuperAdmin())
                        <x-badge variant="neutral">Super Admin</x-badge>
                    @endif
                </div>
            </div>
        </div>
    </x-card>

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

    <x-card class="mt-auto border-dashed bg-slate-50">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Tips</p>
        <p class="mt-3 text-sm leading-6 text-slate-600">
            Gunakan filter periode 1, 3, atau 6 bulan untuk memantau performa absensi dengan lebih cepat.
        </p>
    </x-card>
</div>
