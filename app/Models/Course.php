<?php

namespace App\Models;

use App\Enums\CourseStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'thumbnail',
        'category_id',
        'created_by',
        'status',
        'max_students',
        'start_date',
        'end_date',
    ];

    protected function casts(): array
    {
        return [
            'status' => CourseStatus::class,
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    // ─────────────────────────────────────────────
    // Boot
    // ─────────────────────────────────────────────

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Course $course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title);
            }
        });
    }

    // ─────────────────────────────────────────────
    // Relations
    // ─────────────────────────────────────────────

    public function category(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'enrollments')
            ->withPivot(['status', 'progress_percentage', 'enrolled_at', 'completed_at'])
            ->withTimestamps();
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class)->orderBy('order');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    // ─────────────────────────────────────────────
    // Scopes
    // ─────────────────────────────────────────────

    public function scopePublished(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->where('status', CourseStatus::Published);
    }

    public function scopeByTeacher(\Illuminate\Database\Eloquent\Builder $query, int $userId)
    {
        return $query->where('created_by', $userId);
    }

    // ─────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────

    public function getThumbnailUrlAttribute(): string
    {
        return $this->thumbnail
            ? asset('storage/'.$this->thumbnail)
            : 'https://placehold.co/800x450/6366f1/ffffff?text='.urlencode($this->title);
    }

    public function isPublished(): bool
    {
        return $this->status === CourseStatus::Published;
    }

    public function getEnrolledCountAttribute(): int
    {
        return $this->enrollments()->count();
    }

    public function isFull(): bool
    {
        if (! $this->max_students) {
            return false;
        }

        return $this->enrolled_count >= $this->max_students;
    }
}
