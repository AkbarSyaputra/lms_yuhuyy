<?php

namespace App\Models;

use App\Enums\EnrollmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'status',
        'progress_percentage',
        'enrolled_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => EnrollmentStatus::class,
            'enrolled_at' => 'datetime',
            'completed_at' => 'datetime',
            'progress_percentage' => 'integer',
        ];
    }

    // ─────────────────────────────────────────────
    // Relations
    // ─────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    // ─────────────────────────────────────────────
    // Scopes
    // ─────────────────────────────────────────────

    public function scopeActive(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->where('status', EnrollmentStatus::Active);
    }

    public function scopeCompleted(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->where('status', EnrollmentStatus::Completed);
    }

    // ─────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────

    public function isActive(): bool
    {
        return $this->status === EnrollmentStatus::Active;
    }

    public function markCompleted(): void
    {
        $this->update([
            'status' => EnrollmentStatus::Completed,
            'completed_at' => now(),
            'progress_percentage' => 100,
        ]);
    }
}
