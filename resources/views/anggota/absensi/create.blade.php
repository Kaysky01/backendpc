<x-app-layout>
    <x-slot name="header">
        <div>

            <h2 class="font-display text-xl font-semibold text-slate-900">Input Kode</h2>
        </div>
    </x-slot>

    <div class="grid gap-6 xl:grid-cols-[0.9fr_1.1fr]">
        <section class="section-card">
            <x-badge variant="success">Manual / QR Scan</x-badge>
            <h3 class="mt-4 font-display text-2xl font-semibold text-slate-900">Masukkan kode 6 karakter</h3>
            <p class="mt-3 text-sm leading-6 text-slate-500">
                Sistem akan memeriksa apakah kode 6 karakter tersedia, masih aktif, belum kedaluwarsa, Anda ditugaskan, dan belum pernah Anda pakai untuk kegiatan yang sama.
            </p>

            <form method="POST" action="{{ route('anggota.absensi.store') }}" class="mt-6 space-y-5" id="attendance-form">
                @csrf

                <div>
                    <x-input-label for="kode" value="Kode Absensi" />
                    <x-text-input id="kode" name="kode" type="text" maxlength="6" :value="old('kode', $prefilledCode)" required class="uppercase tracking-[0.3em]" placeholder="ABC123" />
                    <x-input-error :messages="$errors->get('kode')" class="mt-2" />
                </div>

                <x-primary-button>
                    Simpan Kehadiran
                </x-primary-button>
            </form>
        </section>

        <section class="section-card">
            <h3 class="font-display text-2xl font-semibold text-slate-900">Scan QR & Aturan Validasi</h3>
            <div class="mt-6 rounded-2xl border border-gray-200 bg-slate-50 p-4">
                <div id="qr-reader" class="overflow-hidden rounded-2xl"></div>
                <p id="qr-status" class="mt-3 text-sm text-slate-500">Arahkan kamera ke QR code untuk mengisi kode otomatis.</p>
            </div>
            <div class="mt-6 space-y-4">
                <div class="info-tile rounded-2xl">
                    <p class="font-semibold text-slate-900">Kode harus tersedia</p>
                    <p class="mt-2 text-sm text-slate-500">Jika kode tidak ditemukan atau tidak aktif, sistem akan menampilkan pesan kode tidak valid.</p>
                </div>
                <div class="info-tile rounded-2xl">
                    <p class="font-semibold text-slate-900">Kode wajib aktif dan belum expired</p>
                    <p class="mt-2 text-sm text-slate-500">Masa aktif kode mengikuti durasi yang dipilih admin saat generate.</p>
                </div>
                <div class="info-tile rounded-2xl">
                    <p class="font-semibold text-slate-900">Assignment wajib sesuai</p>
                    <p class="mt-2 text-sm text-slate-500">Jika Anda tidak ditugaskan pada kegiatan tersebut, absensi otomatis ditolak.</p>
                </div>
                <div class="info-tile rounded-2xl">
                    <p class="font-semibold text-slate-900">Absensi ganda tidak diizinkan</p>
                    <p class="mt-2 text-sm text-slate-500">Setiap anggota hanya dapat memiliki satu record absensi per kegiatan.</p>
                </div>
                
            </div>
        </section>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
        <script>
            const attendanceForm = document.getElementById('attendance-form');
            const codeInput = document.getElementById('kode');
            const qrStatus = document.getElementById('qr-status');

            if (window.Html5Qrcode && document.getElementById('qr-reader')) {
                const qrScanner = new Html5Qrcode('qr-reader');

                qrScanner.start(
                    { facingMode: 'environment' },
                    { fps: 10, qrbox: 220 },
                    (decodedText) => {
                        const code = decodedText.trim().toUpperCase();
                        codeInput.value = code;
                        qrStatus.textContent = `QR terdeteksi: ${code}. Mengirim absensi...`;
                        qrScanner.stop().catch(() => {});
                        attendanceForm.submit();
                    },
                    () => {}
                ).catch(() => {
                    qrStatus.textContent = 'Kamera tidak tersedia. Anda tetap bisa input kode secara manual.';
                });
            }
        </script>
    @endpush
</x-app-layout>
