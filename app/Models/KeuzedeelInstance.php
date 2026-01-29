<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KeuzedeelInstance extends Model
{
    use HasFactory;

    protected $fillable = [
        'keuzedeel_id',
        'period_id',
        'instance_number',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'instance_number' => 'integer',
    ];

    /**
     * The keuzedeel this instance belongs to
     */
    public function keuzedeel(): BelongsTo
    {
        return $this->belongsTo(Keuzedeel::class);
    }

    /**
     * The period this instance is scheduled for
     */
    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }

    /**
     * All enrollments for this instance
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Active enrollments (not cancelled)
     */
    public function activeEnrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class)
            ->whereIn('status', ['enrolled', 'completed']);
    }

    /**
     * Scope for active instances
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope with active keuzedeel
     */
    public function scopeWithActiveKeuzedeel($query)
    {
        return $query->whereHas('keuzedeel', function ($q) {
            $q->where('is_active', true);
        });
    }

    /**
     * Get the current enrollment count
     */
    public function getEnrollmentCountAttribute(): int
    {
        return $this->activeEnrollments()->count();
    }

    /**
     * Get available spots
     */
    public function getAvailableSpotsAttribute(): int
    {
        return max(0, $this->keuzedeel->max_students - $this->enrollment_count);
    }

    /**
     * Check if instance is full
     */
    public function isFull(): bool
    {
        return $this->enrollment_count >= $this->keuzedeel->max_students;
    }

    /**
     * Check if instance has enough students to start
     */
    public function hasMinimumStudents(): bool
    {
        return $this->enrollment_count >= $this->keuzedeel->min_students;
    }

    /**
     * Get fill percentage
     */
    public function getFillPercentageAttribute(): float
    {
        if ($this->keuzedeel->max_students === 0) {
            return 100;
        }
        return round(($this->enrollment_count / $this->keuzedeel->max_students) * 100, 1);
    }

    /**
     * Get display name with instance number
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->keuzedeel->is_repeatable && $this->instance_number > 1) {
            return "{$this->keuzedeel->name} {$this->instance_number}";
        }
        return $this->keuzedeel->name;
    }
}
