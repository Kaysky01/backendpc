@php
    $selectedAssignedUserIds = collect(old('assigned_user_ids', $kegiatan?->assignedUsers?->pluck('id')->all() ?? []))
        ->map(fn ($id) => (int) $id)
        ->all();
    $anggotaOptions = collect($anggotaList)
        ->map(fn ($item) => [
            'id' => $item->id,
            'name' => $item->name,
            'npm' => $item->anggota?->npm,
            'divisi' => $item->divisi,
            'label' => trim($item->name.($item->anggota?->npm ? ' - '.$item->anggota->npm : '').($item->divisi ? ' • '.$item->divisi : '')),
            'search' => strtolower(trim($item->name.' '.$item->email.' '.($item->anggota?->npm ?? '').' '.($item->divisi ?? ''))),
        ])
        ->values();
@endphp

<div class="grid gap-5 md:grid-cols-2">
    <div class="md:col-span-2">
        <x-input-label for="nama_kegiatan" value="Nama Kegiatan" />
        <x-text-input id="nama_kegiatan" name="nama_kegiatan" type="text" :value="old('nama_kegiatan', $kegiatan?->nama_kegiatan)" required placeholder="Workshop desain, rapat bulanan, dsb." />
        <x-input-error :messages="$errors->get('nama_kegiatan')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="tanggal" value="Tanggal Kegiatan" />
        <x-text-input id="tanggal" name="tanggal" type="date" :value="old('tanggal', optional($kegiatan?->tanggal)->format('Y-m-d'))" required />
        <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="lokasi" value="Lokasi" />
        <x-text-input id="lokasi" name="lokasi" type="text" :value="old('lokasi', $kegiatan?->lokasi)" required placeholder="Gedung, ruangan, atau lokasi kegiatan" />
        <x-input-error :messages="$errors->get('lokasi')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="deskripsi" value="Deskripsi" />
        <textarea id="deskripsi" name="deskripsi" rows="5" class="form-control">{{ old('deskripsi', $kegiatan?->deskripsi) }}</textarea>
        <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="assigned_user_ids" value="Anggota Ditugaskan" />
        <div
            x-data="memberMultiSelect(@js($anggotaOptions), @js($selectedAssignedUserIds))"
            class="mt-2"
        >
            <div class="rounded-2xl border border-gray-300 bg-white shadow-sm" @click.outside="open = false">
                <button
                    type="button"
                    class="flex w-full items-center justify-between gap-3 px-4 py-3 text-left transition duration-200 hover:bg-sky-50/60"
                    @click="open = ! open"
                >
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-slate-900" x-text="summaryText()"></p>
                        <p class="mt-1 text-xs text-slate-500">Cari berdasarkan nama, email, atau NPM. Anggota terpilih akan tampil sebagai tag.</p>
                    </div>
                    <svg class="h-5 w-5 text-slate-400 transition" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                    </svg>
                </button>

                <div class="border-t border-gray-200 px-4 pb-4 pt-3">
                    <div class="flex flex-wrap gap-2" x-show="selectedItems().length > 0">
                        <template x-for="item in selectedItems()" :key="`tag-${item.id}`">
                            <span class="picker-tag">
                                <span x-text="item.name"></span>
                                <button type="button" class="text-sky-700 transition hover:text-sky-900" @click="remove(item.id)" aria-label="Hapus anggota terpilih">×</button>
                            </span>
                        </template>
                    </div>
                    <p class="text-sm text-slate-400" x-show="selectedItems().length === 0">Belum ada anggota dipilih.</p>
                </div>

                <div x-show="open" x-transition.opacity class="border-t border-gray-200 p-4">
                    <input
                        type="text"
                        class="form-control mt-0"
                        x-model="search"
                        placeholder="Cari nama, email, NPM, atau divisi"
                    >

                    <div class="soft-scroll mt-3 max-h-64 space-y-2 overflow-y-auto">
                        <template x-for="item in filteredOptions()" :key="item.id">
                            <button
                                type="button"
                                class="picker-option"
                                :class="isSelected(item.id) ? 'picker-option-active' : ''"
                                @click="toggle(item.id)"
                            >
                                <div class="min-w-0">
                                    <p class="font-medium text-slate-900" x-text="item.name"></p>
                                    <p class="mt-1 text-xs text-slate-500" x-text="[item.npm, item.divisi].filter(Boolean).join(' • ') || 'Data anggota belum lengkap'"></p>
                                </div>
                                <span
                                    class="mt-0.5 inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full border text-[11px] font-bold"
                                    :class="isSelected(item.id) ? 'border-sky-200 bg-sky-100 text-sky-700' : 'border-gray-200 bg-white text-slate-400'"
                                >
                                    <span x-text="isSelected(item.id) ? '✓' : '+'"></span>
                                </span>
                            </button>
                        </template>

                        <p class="rounded-xl border border-dashed border-gray-200 px-4 py-5 text-center text-sm text-slate-500" x-show="filteredOptions().length === 0">
                            Tidak ada anggota yang cocok dengan pencarian.
                        </p>
                    </div>
                </div>
            </div>

            <template x-for="id in selectedIds" :key="`input-${id}`">
                <input type="hidden" name="assigned_user_ids[]" :value="id">
            </template>
        </div>
        <p class="mt-2 text-xs text-slate-500">Pilih anggota yang ditugaskan. Anggota lain tetap dapat melihat kegiatan, tetapi statusnya menjadi tidak ditugaskan.</p>
        <x-input-error :messages="$errors->get('assigned_user_ids')" class="mt-2" />
        <x-input-error :messages="$errors->get('assigned_user_ids.*')" class="mt-2" />
    </div>
</div>

<div class="mt-6 flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:items-center sm:justify-between">
    <a href="{{ route('admin.kegiatan.index') }}" class="btn-secondary w-full sm:w-auto">Kembali</a>
    <x-primary-button class="w-full sm:w-auto" data-loading-label="Menyimpan...">{{ $submitLabel }}</x-primary-button>
</div>
