<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-slate-500">Super Admin</p>
            <h2 class="font-display text-3xl font-semibold text-slate-900">Edit Admin</h2>
        </div>
    </x-slot>

    <section class="section-card">
        <h3 class="font-display text-2xl font-semibold text-slate-900">{{ $admin->name }}</h3>
        <p class="mt-2 text-sm text-slate-500">Perbarui detail akun admin dan hak super admin bila diperlukan.</p>

        <form method="POST" action="{{ route('admin.admins.update', $admin) }}" class="mt-6">
            @csrf
            @method('PUT')
            @include('admin.admins._form', ['submitLabel' => 'Perbarui Admin'])
        </form>
    </section>
</x-app-layout>
