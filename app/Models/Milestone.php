<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Milestone extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
        'start_date',
        'deadline',
        'manual_override',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'deadline' => 'date',
            'manual_override' => 'boolean',
        ];
    }

    // ──────────────────────────────────────────────
    //  Relationships
    // ──────────────────────────────────────────────

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    // ──────────────────────────────────────────────
    //  Helpers
    // ──────────────────────────────────────────────

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}
