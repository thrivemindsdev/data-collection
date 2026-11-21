<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $fillable = ['bhakt_id', 'action', 'actor_id', 'remarks'];

    public function bhakt(): BelongsTo
    {
        return $this->belongsTo(Bhakt::class);
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}
