<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    protected $fillable = [
        'natal_chart_id',
        'role',
        'content',
    ];

    public function natalChart(): BelongsTo
    {
        return $this->belongsTo(NatalChart::class);
    }
}
