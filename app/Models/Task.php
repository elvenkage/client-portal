<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\ActivityLog;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    // ──────────────────────────────────────────────
    //  Status & Review Constants
    // ──────────────────────────────────────────────

    public const STATUS_TODO = 'todo';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_REVIEW = 'review';
    public const STATUS_COMPLETED = 'completed';

    public const REVIEW_NONE = 'none';
    public const REVIEW_PM = 'pm_review';
    public const REVIEW_CLIENT = 'client_review';

    protected $fillable = [
        'project_id',
        'milestone_id',
        'assigned_to',
        'title',
        'description',
        'status',
        'priority',
        'review_stage',
        'client_review_required',
        'client_review_deadline',
        'start_date',
        'deadline',
    ];

    protected function casts(): array
    {
        return [
            'client_review_required' => 'boolean',
            'client_review_deadline' => 'datetime',
            'start_date' => 'date',
            'deadline' => 'date',
        ];
    }

    // ──────────────────────────────────────────────
    //  Workflow Helpers
    // ──────────────────────────────────────────────

    public function isReviewable(): bool
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    public function isPmReview(): bool
    {
        return $this->status === self::STATUS_REVIEW
            && $this->review_stage === self::REVIEW_PM;
    }

    public function isClientReview(): bool
    {
        return $this->status === self::STATUS_REVIEW
            && $this->review_stage === self::REVIEW_CLIENT;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isClientReviewExpired(): bool
    {
        return $this->isClientReview()
            && $this->client_review_deadline
            && $this->client_review_deadline->isPast();
    }

    // ──────────────────────────────────────────────
    //  Relationships
    // ──────────────────────────────────────────────

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function milestone(): BelongsTo
    {
        return $this->belongsTo(Milestone::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(Subtask::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function activities(): MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'target');
    }
}
