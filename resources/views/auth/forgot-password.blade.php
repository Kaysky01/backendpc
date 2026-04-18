<x-guest-layout>
    <div class="auth-card">
        <div class="mb-8">
            <x-badge variant="neutral">Reset Password</x-badge>
            <h2 class="mt-4 font-display text-3xl font-semibold text-slate-900">Lupa password?</h2>
            <p class="mt-3 text-sm leading-6 text-slate-500">
                Masukkan email akun Anda dan kami akan mengirim tautan untuk membuat password baru.
            </p>
        </div>

        <x-auth-session-status class="mb-5" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus placeholder="nama@polinela.ac.id" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="flex justify-end border-t border-slate-100 pt-4">
                <x-primary-button>
                    Kirim Link Reset
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
