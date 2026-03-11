<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'company_name',
        'email',
        'phone',
        'address',
        'notes',
    ];

    // ──────────────────────────────────────────────
    //  Relationships
    // ──────────────────────────────────────────────

    /**
     * Projects belonging to this client.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Users belonging to this client.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
