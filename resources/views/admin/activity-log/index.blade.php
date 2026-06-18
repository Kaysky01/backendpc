<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-display text-xl font-semibold text-slate-900">Riwayat Aktivitas</h2>
        </div>
    </x-slot>

    <div class="space-y-6">
        <section class="section-card">
            <div>
                <h3 class="font-display text-2xl font-semibold text-slate-900">Semua Aktivitas</h3>
                <p class="mt-2 text-sm text-slate-500">Log lengkap semua aktivitas yang terjadi di sistem.</p>
            </div>

            <div class="mt-6 overflow-x-auto">
                <table class="table-base">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>User</th>
                            <th>Aksi</th>
                            <th>Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td class="whitespace-nowrap">{{ $log->created_at->format('d M Y H:i:s') }}</td>
                                <td>
                                    @if ($log->user)
                                        <span class="font-semibold text-slate-900">{{ $log->user->name }}</span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $actionClass = match(true) {
                                            str_contains($log->action, 'import') => 'badge-info',
                                            str_contains($log->action, 'create') => 'badge-success',
                                            str_contains($log->action, 'update') => 'badge-warning',
                                            str_contains($log->action, 'delete') => 'badge-danger',
                                            str_contains($log->action, 'rollback') => 'badge-danger',
                                            default => 'badge-neutral',
                                        };
                                        $actionLabel = str($log->action)->replace('_', ' ')->title();
                                    @endphp
                                    <span class="badge {{ $actionClass }}">{{ $actionLabel }}</span>
                                </td>
                                <td class="max-w-lg text-slate-600">{{ $log->description }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-slate-500">Belum ada riwayat aktivitas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $logs->links() }}
        </section>
    </div>
</x-app-layout>
