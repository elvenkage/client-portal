<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    /**
     * Activity logs are append-only – no updated_at needed.
     */
    public $timestamps = false;

    protected $fillable = [
        'project_id',
        'user_id',
        'action',
        'target_type',
        'target_id',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (ActivityLog $log) {
            $log->created_at = $log->created_at ?? now();
        });
    }

    // ──────────────────────────────────────────────
    //  Relationships
    // ──────────────────────────────────────────────

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Polymorphic target (Task, Milestone, File, Comment, etc.).
     */
    public function target(): MorphTo
    {
        return $this->morphTo();
    }
}
