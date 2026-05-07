<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlertRead extends Model
{
    public $timestamps = false;

    protected $fillable = ['alert_id', 'user_id', 'read_at'];

    protected $casts = ['read_at' => 'datetime'];

    public function alert(): BelongsTo
    {
        return $this->belongsTo(Alert::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
