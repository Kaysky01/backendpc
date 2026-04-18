<?php

namespace App\Models;

use Database\Factories\AnggotaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    /** @use HasFactory<AnggotaFactory> */
    use HasFactory;

    protected $table = 'anggota';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'npm',
        'prodi',
        'angkatan',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
