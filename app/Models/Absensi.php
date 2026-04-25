<?php

namespace App\Models;

use Database\Factories\AbsensiFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    /** @use HasFactory<AbsensiFactory> */
    use HasFactory;

    public const STATUS_HADIR = 'hadir';

    public const STATUS_IZIN = 'izin';

    public const STATUS_ALFA = 'alfa';

    public const STATUS_TIDAK_DITUGASKAN = 'tidak_ditugaskan';

    protected $table = 'absensi';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'kegiatan_id',
        'status',
        'waktu_absen',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'waktu_absen' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(Kegiatan::class);
    }

    /**
     * @return list<string>
     */
    public static function statusOptions(): array
    {
        return [
            self::STATUS_HADIR,
            self::STATUS_IZIN,
            self::STATUS_ALFA,
            self::STATUS_TIDAK_DITUGASKAN,
        ];
    }

    /**
     * @return list<string>
     */
    public static function manualStatusOptions(): array
    {
        return [
            self::STATUS_HADIR,
            self::STATUS_IZIN,
            self::STATUS_ALFA,
        ];
    }
}
