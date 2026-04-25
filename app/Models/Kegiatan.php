<?php

namespace App\Models;

use Database\Factories\KegiatanFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Kegiatan extends Model
{
    /** @use HasFactory<KegiatanFactory> */
    use HasFactory;

    protected $table = 'kegiatan';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'nama_kegiatan',
        'tanggal',
        'lokasi',
        'deskripsi',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    public function kodeAbsensis(): HasMany
    {
        return $this->hasMany(KodeAbsensi::class);
    }

    public function latestCode(): HasOne
    {
        return $this->hasOne(KodeAbsensi::class)->latestOfMany();
    }

    public function assignedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'kegiatan_anggota')
            ->withTimestamps();
    }
}
