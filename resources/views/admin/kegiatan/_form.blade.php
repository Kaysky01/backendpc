@php
    $selectedAssignedUserIds = collect(old('assigned_user_ids', $kegiatan?->assignedUsers?->pluck('id')->all() ?? []))
        ->map(fn ($id) => (int) $id)
        ->all();
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
        <select id="assigned_user_ids" name="assigned_user_ids[]" class="form-control" multiple size="8">
            @foreach ($anggotaList as $item)
                <option value="{{ $item->id }}" @selected(in_array($item->id, $selectedAssignedUserIds, true))>
                    {{ $item->name }}{{ $item->divisi ? ' - '.$item->divisi : '' }}
                </option>
            @endforeach
        </select>
        <p class="mt-2 text-xs text-slate-500">Pilih anggota yang ditugaskan. Anggota lain tetap dapat melihat kegiatan, tetapi statusnya menjadi tidak ditugaskan.</p>
        <x-input-error :messages="$errors->get('assigned_user_ids')" class="mt-2" />
        <x-input-error :messages="$errors->get('assigned_user_ids.*')" class="mt-2" />
    </div>
</div>

<div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-5">
    <a href="{{ route('admin.kegiatan.index') }}" class="btn-secondary">Kembali</a>
    <x-primary-button>{{ $submitLabel }}</x-primary-button>
</div>
