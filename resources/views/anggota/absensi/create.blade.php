<x-app-layout>
    <x-slot name="header">
        <div>

            <h2 class="font-display text-xl font-semibold text-slate-900">Input Kode</h2>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-[0.9fr_1.1fr]">
        <section class="section-card">
            <x-badge variant="success">Manual / QR Scan</x-badge>
            <h3 class="mt-4 font-display text-2xl font-semibold text-slate-900">Masukkan kode 6 karakter</h3>
            <p class="mt-3 text-sm leading-6 text-slate-500">
                Sistem akan memeriksa apakah kode 6 karakter tersedia, masih aktif, belum kedaluwarsa, Anda ditugaskan, dan belum pernah Anda pakai untuk kegiatan yang sama.
            </p>

            <form method="POST" action="{{ route('anggota.absensi.store') }}" class="mt-6 space-y-5" id="attendance-form" data-loading-form>
                @csrf

                <div>
                    <x-input-label for="kode" value="Kode Absensi" />
                    <x-text-input
                        id="kode"
                        name="kode"
                        type="text"
                        maxlength="6"
                        inputmode="text"
                        autocomplete="off"
                        spellcheck="false"
                        data-auto-uppercase
                        data-alpha-numeric="true"
                        data-max-length="6"
                        :value="old('kode', $prefilledCode)"
                        required
                        class="uppercase tracking-[0.3em] text-center sm:text-left"
                        placeholder="ABC123"
                    />
                    <p class="mt-2 text-xs text-slate-500">Kode otomatis diubah ke huruf besar dan dibatasi 6 karakter.</p>
                    <x-input-error :messages="$errors->get('kode')" class="mt-2" />
                </div>

                <x-primary-button class="w-full sm:w-auto" data-loading-label="Memeriksa kode...">
                    Simpan Kehadiran
                </x-primary-button>
            </form>
        </section>

        <section class="section-card">
            <h3 class="font-display text-2xl font-semibold text-slate-900">Scan QR & Aturan Validasi</h3>
            <div class="mt-6 rounded-2xl border border-gray-200 bg-slate-50 p-4">
                <div class="flex flex-col gap-3 sm:flex-row">
                    <button type="button" id="start-scan" class="btn-primary w-full sm:w-auto">Scan QR</button>
                    <button type="button" id="stop-scan" class="btn-secondary w-full sm:w-auto" disabled>Stop Scan</button>
                </div>
                <div
                    id="qr-reader"
                    class="mt-4 min-h-[220px] overflow-hidden rounded-2xl border border-dashed border-sky-200 bg-white transition-all duration-200"
                ></div>
                <p id="qr-status" class="mt-3 text-sm text-slate-500">Tekan tombol scan untuk meminta izin kamera lalu arahkan ke QR code.</p>
                <p id="qr-hint" class="mt-2 text-xs text-slate-400">Kamera hanya dapat dijalankan melalui HTTPS atau localhost.</p>
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
            const qrReader = document.getElementById('qr-reader');
            const qrHint = document.getElementById('qr-hint');
            const startScanButton = document.getElementById('start-scan');
            const stopScanButton = document.getElementById('stop-scan');
            const isSecureCameraContext = window.isSecureContext || ['localhost', '127.0.0.1', '::1', '[::1]'].includes(window.location.hostname);

            let qrScanner = null;
            let isScanning = false;
            let noDetectionTimeoutId = null;
            let qrStatusTimeoutId = null;

            const setQrStatus = (message, tone = 'muted') => {
                qrStatus.textContent = message;
                qrStatus.className = 'mt-3 text-sm';

                if (tone === 'error') {
                    qrStatus.classList.add('font-medium', 'text-red-600');
                    return;
                }

                if (tone === 'success') {
                    qrStatus.classList.add('font-medium', 'text-sky-600');
                    return;
                }

                qrStatus.classList.add('text-slate-500');
            };

            const setReaderState = (active) => {
                qrReader.classList.toggle('ring-2', active);
                qrReader.classList.toggle('ring-sky-200', active);
                qrReader.classList.toggle('bg-sky-50', active);
            };

            const qrBoxSize = () => {
                const width = window.innerWidth || 360;

                if (width < 420) {
                    return { width: 180, height: 180 };
                }

                if (width < 768) {
                    return { width: 220, height: 220 };
                }

                return { width: 260, height: 260 };
            };

            const primeInlineVideo = () => {
                window.setTimeout(() => {
                    const video = qrReader.querySelector('video');

                    if (!video) {
                        return;
                    }

                    video.setAttribute('playsinline', 'true');
                    video.setAttribute('muted', 'true');
                    video.setAttribute('autoplay', 'true');
                }, 120);
            };

            const syncScanButtons = () => {
                startScanButton.disabled = isScanning;
                stopScanButton.disabled = !isScanning;
            };

            const clearStatusTimeout = () => {
                if (qrStatusTimeoutId) {
                    window.clearTimeout(qrStatusTimeoutId);
                    qrStatusTimeoutId = null;
                }
            };

            const clearDetectionTimeout = () => {
                if (noDetectionTimeoutId) {
                    window.clearTimeout(noDetectionTimeoutId);
                    noDetectionTimeoutId = null;
                }
            };

            const scheduleNoDetectionMessage = () => {
                clearDetectionTimeout();
                noDetectionTimeoutId = window.setTimeout(() => {
                    if (isScanning) {
                        setQrStatus('QR tidak terdeteksi. Coba dekatkan kamera atau atur pencahayaan.', 'error');
                        clearStatusTimeout();
                        qrStatusTimeoutId = window.setTimeout(() => {
                            if (isScanning) {
                                setQrStatus('Kamera aktif. Arahkan ke QR code atau gunakan input manual jika kamera tidak tersedia.', 'muted');
                            }
                        }, 3500);
                    }
                }, 9000);
            };

            const stopScanner = async (statusMessage = 'Scan dihentikan. Anda tetap bisa input kode secara manual.', tone = 'muted') => {
                clearDetectionTimeout();
                clearStatusTimeout();

                if (!qrScanner || !isScanning) {
                    isScanning = false;
                    syncScanButtons();
                    setReaderState(false);
                    setQrStatus(statusMessage, tone);
                    return;
                }

                try {
                    await qrScanner.stop();
                    await qrScanner.clear();
                } catch (_) {
                    // Abaikan error stop agar UX tetap halus.
                }

                qrScanner = null;
                isScanning = false;
                qrReader.innerHTML = '';
                setReaderState(false);
                syncScanButtons();
                setQrStatus(statusMessage, tone);
            };

            const startHtml5Scanner = async (cameraConfig) => {
                qrScanner = new Html5Qrcode('qr-reader');
                isScanning = true;
                syncScanButtons();
                setReaderState(true);
                setQrStatus('Memulai kamera...', 'muted');

                await qrScanner.start(
                    cameraConfig,
                    {
                        fps: 10,
                        qrbox: qrBoxSize(),
                        aspectRatio: 1,
                    },
                    async (decodedText) => {
                        const code = decodedText.trim().toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 6);

                        if (code.length === 0) {
                            return;
                        }

                        codeInput.value = code;
                        codeInput.dispatchEvent(new Event('input', { bubbles: true }));
                        setQrStatus(`QR terdeteksi: ${code}. Mengirim absensi...`, 'success');
                        await stopScanner('QR terdeteksi. Mengirim absensi...', 'success');
                        if (typeof attendanceForm.requestSubmit === 'function') {
                            attendanceForm.requestSubmit();
                            return;
                        }

                        attendanceForm.submit();
                    },
                    () => {}
                );

                primeInlineVideo();
                setQrStatus('Kamera aktif. Arahkan ke QR code untuk mengisi kode otomatis.', 'success');
                scheduleNoDetectionMessage();
            };

            const startScanner = async () => {
                if (!navigator.mediaDevices?.getUserMedia) {
                    setQrStatus('Browser tidak mendukung kamera. Gunakan input manual jika kamera tidak tersedia.', 'error');
                    return;
                }

                if (!isSecureCameraContext) {
                    setQrStatus('Gunakan HTTPS untuk scan QR. QR Scanner hanya dapat digunakan pada koneksi HTTPS atau localhost.', 'error');
                    return;
                }

                if (!window.Html5Qrcode) {
                    setQrStatus('Browser tidak mendukung kamera. Gunakan input manual jika kamera tidak tersedia.', 'error');
                    return;
                }

                if (isScanning) {
                    return;
                }

                try {
                    setQrStatus('Meminta izin kamera...', 'muted');
                    qrHint.textContent = 'Saat izin muncul, pilih izinkan agar scanner dapat memakai kamera belakang.';

                    const permissionStream = await navigator.mediaDevices.getUserMedia({ video: true });

                    permissionStream.getTracks().forEach((track) => track.stop());
                    await startHtml5Scanner({ facingMode: { exact: 'environment' } });
                } catch (error) {
                    if (error?.name === 'OverconstrainedError' || error?.name === 'NotFoundError' || error?.name === 'DevicesNotFoundError') {
                        try {
                            if (qrScanner) {
                                try {
                                    await qrScanner.clear();
                                } catch (_) {
                                    // Abaikan error cleanup sebelum fallback camera mode.
                                }
                            }

                            qrScanner = null;
                            isScanning = false;
                            syncScanButtons();
                            setReaderState(false);

                            await startHtml5Scanner({ facingMode: { ideal: 'environment' } });
                            return;
                        } catch (fallbackError) {
                            error = fallbackError;
                        }
                    }

                    isScanning = false;
                    qrScanner = null;
                    syncScanButtons();
                    qrReader.innerHTML = '';
                    setReaderState(false);

                    if (error?.name === 'NotAllowedError' || error?.name === 'PermissionDeniedError') {
                        setQrStatus('Izin kamera ditolak. Silakan izinkan akses kamera lalu coba lagi.', 'error');
                        return;
                    }

                    if (error?.name === 'NotFoundError' || error?.name === 'DevicesNotFoundError') {
                        setQrStatus('Kamera tidak tersedia. Gunakan input manual jika kamera tidak tersedia.', 'error');
                        return;
                    }

                    if (error?.name === 'NotReadableError' || error?.name === 'TrackStartError') {
                        setQrStatus('Kamera sedang dipakai aplikasi lain atau tidak bisa diakses. Gunakan input manual jika kamera tidak tersedia.', 'error');
                        return;
                    }

                    setQrStatus('Browser tidak mendukung kamera atau scanner gagal dijalankan. Gunakan input manual jika kamera tidak tersedia.', 'error');
                }
            };

            if (!isSecureCameraContext) {
                setQrStatus('QR Scanner hanya dapat digunakan pada koneksi HTTPS atau localhost.', 'error');
                qrHint.textContent = 'Gunakan input manual jika kamera tidak tersedia atau halaman belum berjalan di HTTPS.';
            }

            syncScanButtons();

            startScanButton?.addEventListener('click', startScanner);
            stopScanButton?.addEventListener('click', () => stopScanner());

            window.addEventListener('beforeunload', () => {
                stopScanner('', 'muted');
            });
        </script>
    @endpush
</x-app-layout>
