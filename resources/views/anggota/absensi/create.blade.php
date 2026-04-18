<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-slate-500">Anggota Panel</p>
            <h2 class="font-display text-3xl font-semibold text-slate-900">Input Kode Absensi</h2>
        </div>
    </x-slot>

    <div class="grid gap-6 xl:grid-cols-[0.9fr_1.1fr]">
        <section class="section-card">
            <x-badge variant="success">Kode Aktif 15 Menit</x-badge>
            <h3 class="mt-4 font-display text-2xl font-semibold text-slate-900">Masukkan kode 6 karakter</h3>
            <p class="mt-3 text-sm leading-6 text-slate-500">
                Sistem akan memeriksa apakah kode 6 karakter tersedia, masih aktif, belum kedaluwarsa, dan belum pernah Anda pakai untuk kegiatan yang sama.
            </p>

            <form method="POST" action="{{ route('anggota.absensi.store') }}" class="mt-6 space-y-5">
                @csrf

                <div>
                    <x-input-label for="kode" value="Kode Absensi" />
                    <x-text-input id="kode" name="kode" type="text" maxlength="6" :value="old('kode')" required class="uppercase tracking-[0.3em]" placeholder="ABC123" />
                    <x-input-error :messages="$errors->get('kode')" class="mt-2" />
                </div>

                <x-primary-button>
                    Simpan Kehadiran
                </x-primary-button>
            </form>
        </section>

        <section class="section-card">
            <h3 class="font-display text-2xl font-semibold text-slate-900">Aturan Validasi</h3>
            <div class="mt-6 space-y-4">
                <div class="info-tile rounded-2xl">
                    <p class="font-semibold text-slate-900">Kode harus tersedia</p>
                    <p class="mt-2 text-sm text-slate-500">Jika kode tidak ditemukan di database, absensi akan ditolak.</p>
                </div>
                <div class="info-tile rounded-2xl">
                    <p class="font-semibold text-slate-900">Kode wajib aktif dan belum expired</p>
                    <p class="mt-2 text-sm text-slate-500">Masa aktif kode dibatasi 15 menit sejak dibuat admin.</p>
                </div>
                <div class="info-tile rounded-2xl">
                    <p class="font-semibold text-slate-900">Absensi ganda tidak diizinkan</p>
                    <p class="mt-2 text-sm text-slate-500">Setiap anggota hanya dapat memiliki satu record absensi per kegiatan.</p>
                </div>
                <div class="info-tile rounded-2xl">
                    <p class="font-semibold text-slate-900">Timestamp disimpan otomatis</p>
                    <p class="mt-2 text-sm text-slate-500">Saat valid, sistem menyimpan `waktu_absen` aktual dengan format datetime penuh.</p>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
