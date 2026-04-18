<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-slate-500">Admin Panel</p>
            <h2 class="font-display text-3xl font-semibold text-slate-900">Manajemen Anggota</h2>
        </div>
    </x-slot>

    <div class="space-y-6">
        <section class="section-card">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="font-display text-2xl font-semibold text-slate-900">Daftar Anggota</h3>
                    <p class="mt-2 text-sm text-slate-500">Kelola akun anggota lengkap dengan NPM, prodi, dan angkatan.</p>
                </div>
                <a href="{{ route('admin.anggota.create') }}" class="btn-primary">Tambah Anggota</a>
            </div>

            <form method="GET" class="mt-6 grid gap-4 md:grid-cols-[1fr_auto]">
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    class="form-control mt-0"
                    placeholder="Cari nama, email, NPM, prodi, atau angkatan"
                >
                <button type="submit" class="btn-secondary">Filter</button>
            </form>
        </section>

        <section class="table-shell">
            <div class="overflow-x-auto">
                <table class="table-base">
                    <thead>
                        <tr>
                            <th>Anggota</th>
                            <th>NPM</th>
                            <th>Program Studi</th>
                            <th>Angkatan</th>
                            <th>Email</th>
                            <th class="w-44">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($anggotas as $item)
                            <tr>
                                <td>
                                    <p class="font-semibold text-slate-900">{{ $item->name }}</p>
                                    <p class="mt-1 text-sm text-slate-500">Terdaftar {{ $item->created_at->format('d M Y') }}</p>
                                </td>
                                <td>{{ $item->anggota?->npm ?? '-' }}</td>
                                <td>{{ $item->anggota?->prodi ?? '-' }}</td>
                                <td>{{ $item->anggota?->angkatan ?? '-' }}</td>
                                <td>{{ $item->email }}</td>
                                <td>
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.anggota.edit', $item) }}" class="btn-secondary !px-4 !py-2">Edit</a>
                                        <form method="POST" action="{{ route('admin.anggota.destroy', $item) }}" onsubmit="return confirm('Hapus anggota ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger !px-4 !py-2">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-slate-500">Belum ada anggota yang sesuai dengan filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{ $anggotas->links() }}
    </div>
</x-app-layout>
