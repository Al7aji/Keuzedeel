<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Period extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'academic_year',
        'period_number',
        'start_date',
        'end_date',
        'enrollment_open',
        'enrollment_start',
        'enrollment_end',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'enrollment_open' => 'boolean',
        'enrollment_start' => 'datetime',
        'enrollment_end' => 'datetime',
    ];

    /**
     * Keuzedeel instances in this period
     */
    public function keuzedeelInstances(): HasMany
    {
        return $this->hasMany(KeuzedeelInstance::class);
    }

    /**
     * Scope for periods with open enrollment
     */
    public function scopeEnrollmentOpen($query)
    {
        return $query->where('enrollment_open', true);
    }

    /**
     * Scope for current period
     */
    public function scopeCurrent($query)
    {
        return $query->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    /**
     * Check if enrollment is currently allowed
     */
    public function canEnroll(): bool
    {
        if (!$this->enrollment_open) {
            return false;
        }

        $now = now();

        if ($this->enrollment_start && $now < $this->enrollment_start) {
            return false;
        }

        if ($this->enrollment_end && $now > $this->enrollment_end) {
            return false;
        }

        return true;
    }

    /**
     * Get display name with period info
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->name} ({$this->academic_year} - Periode {$this->period_number})";
    }
}
