<?php

namespace App\Models;

use Database\Factories\KodeAbsensiFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KodeAbsensi extends Model
{
    /** @use HasFactory<KodeAbsensiFactory> */
    use HasFactory;

    protected $table = 'kode_absensi';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'kegiatan_id',
        'kode',
        'expired_at',
        'expired_minutes',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'expired_at' => 'datetime',
            'expired_minutes' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function isExpired(): bool
    {
        return $this->expired_at->isPast();
    }
}
