<x-guest-layout>
    <div class="auth-card">
        <div class="mb-8">
            <x-badge variant="info">Verifikasi Email</x-badge>
            <h2 class="mt-4 font-display text-3xl font-semibold text-slate-900">Periksa kotak masuk Anda</h2>
            <p class="mt-3 text-sm leading-6 text-slate-500">
                Sebelum mulai menggunakan sistem, klik tautan verifikasi yang baru saja dikirim ke email Anda.
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <x-auth-session-status class="mb-5" :status="'Link verifikasi telah dikirim ke email Anda'" />
        @endif

        <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <x-primary-button>
                    Kirim ulang verifikasi email
                </x-primary-button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="text-sm font-medium text-slate-500 transition hover:text-slate-900">
                    Keluar
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
