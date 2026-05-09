<?php

namespace App\Models;

use App\Enums\AssignmentType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'type',
        'max_score',
        'due_date',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'type' => AssignmentType::class,
            'due_date' => 'datetime',
            'is_published' => 'boolean',
            'max_score' => 'integer',
        ];
    }

    // ─────────────────────────────────────────────
    // Relations
    // ─────────────────────────────────────────────

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    // ─────────────────────────────────────────────
    // Scopes
    // ─────────────────────────────────────────────

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('due_date', '>', now())->orderBy('due_date');
    }

    // ─────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────

    public function isPastDue(): bool
    {
        return $this->due_date && $this->due_date->isPast();
    }

    public function submissionByStudent(int $studentId): ?Submission
    {
        return $this->submissions()->where('student_id', $studentId)->first();
    }
}
