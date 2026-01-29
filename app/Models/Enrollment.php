<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'keuzedeel_instance_id',
        'status',
        'enrolled_at',
        'completed_at',
        'cancelled_at',
        'notes',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * The student who enrolled
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Alias for user relationship
     */
    public function student(): BelongsTo
    {
        return $this->user();
    }

    /**
     * The keuzedeel instance this enrollment is for
     */
    public function keuzedeelInstance(): BelongsTo
    {
        return $this->belongsTo(KeuzedeelInstance::class);
    }

    /**
     * Scope for active enrollments
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['enrolled', 'completed']);
    }

    /**
     * Scope for enrolled status only
     */
    public function scopeEnrolled($query)
    {
        return $query->where('status', 'enrolled');
    }

    /**
     * Scope for completed
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for cancelled
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Check if enrollment is active
     */
    public function isActive(): bool
    {
        return in_array($this->status, ['enrolled', 'completed']);
    }

    /**
     * Cancel the enrollment
     */
    public function cancel(): bool
    {
        $this->status = 'cancelled';
        $this->cancelled_at = now();
        return $this->save();
    }

    /**
     * Mark as completed
     */
    public function markCompleted(): bool
    {
        $this->status = 'completed';
        $this->completed_at = now();
        return $this->save();
    }
}
