<x-app-layout>
    <x-slot name="header">
        <div>
            
            <h2 class="font-display text-xl font-semibold text-slate-900">Manajemen Admin</h2>
        </div>
    </x-slot>

    <div class="space-y-6">
        <section class="section-card">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="font-display text-2xl font-semibold text-slate-900">Daftar Admin</h3>
                    <p class="mt-2 text-sm text-slate-500">Hanya super admin yang dapat membuat, mengubah, dan menghapus admin.</p>
                </div>
                <a href="{{ route('admin.admins.create') }}" class="btn-primary h-10 px-4">Tambah Admin</a>
            </div>

            <form method="GET" class="mt-6 grid gap-4 md:grid-cols-[1fr_auto]">
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    class="form-control mt-0"
                    placeholder="Cari nama atau email admin"
                >
                <button type="submit" class="btn-secondary h-10 px-4">Filter</button>
            </form>
        </section>

        <section class="table-shell">
            <div class="overflow-x-auto">
                <table class="table-base">
                    <thead>
                        <tr>
                            <th>Admin</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Login Terakhir</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th class="w-44">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($admins as $item)
                            @php
                                $isOnline = $item->last_login_at && $item->last_login_at->diffInMinutes(now()) < 5;
                            @endphp
                            <tr>
                                <td class="font-semibold text-slate-900">{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>
                                    <span class="badge {{ $item->is_super_admin ? 'badge-success' : 'badge-neutral' }}">
                                        {{ $item->is_super_admin ? 'Super Admin' : 'Admin' }}
                                    </span>
                                </td>
                                <td>
                                    @if ($item->last_login_at)
                                        {{ $item->last_login_at->format('d M Y H:i') }}
                                    @else
                                        <span class="text-slate-400">Belum pernah</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($isOnline)
                                        <span class="inline-flex items-center gap-1.5">
                                            <span class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>
                                            <span class="text-sm font-medium text-green-700">Online</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5">
                                            <span class="h-2 w-2 rounded-full bg-slate-300"></span>
                                            <span class="text-sm text-slate-500">Offline</span>
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $item->created_at->format('d M Y') }}</td>
                                <td class="whitespace-nowrap align-middle">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.admins.edit', $item) }}" class="btn-secondary h-10 px-4">Edit</a>
                                        <form method="POST" action="{{ route('admin.admins.destroy', $item) }}" onsubmit="return confirm('Hapus admin ini?')" data-loading-form>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger h-10 px-4" data-loading-label="Menghapus...">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-slate-500">Belum ada admin yang sesuai dengan filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{ $admins->links() }}
    </div>
</x-app-layout>
