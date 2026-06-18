<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportHistory extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'file_name',
        'imported_count',
        'skipped_count',
        'record_ids',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'record_ids' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
