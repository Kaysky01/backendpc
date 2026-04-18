<div class="grid gap-5 md:grid-cols-2">
    <div>
        <x-input-label for="user_id" value="Anggota" />
        <select id="user_id" name="user_id" class="form-control" required>
            <option value="">Pilih anggota</option>
            @foreach ($anggotaList as $item)
                <option value="{{ $item->id }}" @selected((int) old('user_id', $absensiItem?->user_id) === $item->id)>
                    {{ $item->name }}{{ $item->anggota?->npm ? ' - '.$item->anggota->npm : '' }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="kegiatan_id" value="Kegiatan" />
        <select id="kegiatan_id" name="kegiatan_id" class="form-control" required>
            <option value="">Pilih kegiatan</option>
            @foreach ($kegiatanList as $item)
                <option value="{{ $item->id }}" @selected((int) old('kegiatan_id', $absensiItem?->kegiatan_id) === $item->id)>
                    {{ $item->nama_kegiatan }} - {{ $item->tanggal->format('d M Y') }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('kegiatan_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="status" value="Status" />
        <select id="status" name="status" class="form-control" required>
            @foreach ($statusOptions as $status)
                <option value="{{ $status }}" @selected(old('status', $absensiItem?->status) === $status)>{{ strtoupper($status) }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('status')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="waktu_absen" value="Waktu Absen" />
        <x-text-input
            id="waktu_absen"
            name="waktu_absen"
            type="datetime-local"
            :value="old('waktu_absen', optional($absensiItem?->waktu_absen)->format('Y-m-d\TH:i'))"
        />
        <p class="mt-2 text-xs text-slate-500">Jika dikosongkan, sistem akan memakai waktu saat ini.</p>
        <x-input-error :messages="$errors->get('waktu_absen')" class="mt-2" />
    </div>
</div>

<div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-5">
    <a href="{{ route('admin.absensi.index') }}" class="btn-secondary">Kembali</a>
    <x-primary-button>{{ $submitLabel }}</x-primary-button>
</div>
