<x-guest-layout>
    <div class="auth-card">
        <div class="mb-8">
            <x-badge variant="neutral">Konfirmasi Keamanan</x-badge>
            <h2 class="mt-4 font-display text-3xl font-semibold text-slate-900">Konfirmasi password</h2>
            <p class="mt-3 text-sm leading-6 text-slate-500">
                Area ini memerlukan verifikasi ulang password sebelum Anda melanjutkan.
            </p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
            @csrf

            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex justify-end border-t border-slate-100 pt-4">
                <x-primary-button>
                    Confirm
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
