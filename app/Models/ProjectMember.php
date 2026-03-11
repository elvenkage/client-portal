<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectMember extends Model
{
    use HasFactory;

    /**
     * Disable default timestamps (only created_at is used).
     */
    public $timestamps = false;

    protected $fillable = [
        'project_id',
        'user_id',
        'role',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    // ──────────────────────────────────────────────
    //  Boot – auto-set created_at
    // ──────────────────────────────────────────────

    protected static function booted(): void
    {
        static::creating(function (ProjectMember $member) {
            $member->created_at = $member->created_at ?? now();
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
}
