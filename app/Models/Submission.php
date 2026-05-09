<?php

namespace App\Models;

use App\Enums\SubmissionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'student_id',
        'content',
        'file_path',
        'score',
        'feedback',
        'status',
        'submitted_at',
        'graded_at',
        'graded_by',
    ];

    protected function casts(): array
    {
        return [
            'status' => SubmissionStatus::class,
            'submitted_at' => 'datetime',
            'graded_at' => 'datetime',
            'score' => 'integer',
        ];
    }

    // ─────────────────────────────────────────────
    // Relations
    // ─────────────────────────────────────────────

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function gradedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    // ─────────────────────────────────────────────
    // Scopes
    // ─────────────────────────────────────────────

    public function scopeGraded(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->where('status', SubmissionStatus::Graded);
    }

    public function scopePending(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->where('status', SubmissionStatus::Submitted);
    }

    // ─────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────

    public function isGraded(): bool
    {
        return $this->status === SubmissionStatus::Graded;
    }

    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path ? asset('storage/'.$this->file_path) : null;
    }

    public function getScorePercentageAttribute(): ?float
    {
        if (! $this->score || ! $this->assignment) {
            return null;
        }

        return round(($this->score / $this->assignment->max_score) * 100, 1);
    }
}
