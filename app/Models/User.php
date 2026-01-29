<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'program_id',
        'student_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * The program this user belongs to (for students)
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * All enrollments for this user
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Active enrollments
     */
    public function activeEnrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class)
            ->whereIn('status', ['enrolled', 'completed']);
    }

    /**
     * Check if user is a student
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is an SLB-er
     */
    public function isSlber(): bool
    {
        return $this->role === 'slber';
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    /**
     * Scope for students
     */
    public function scopeStudents($query)
    {
        return $query->where('role', 'student');
    }

    /**
     * Scope for admins
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope for SLB-ers
     */
    public function scopeSlbers($query)
    {
        return $query->where('role', 'slber');
    }

    /**
     * Check if user has completed a keuzedeel
     */
    public function hasCompletedKeuzedeel(int $keuzedeelId): bool
    {
        return $this->enrollments()
            ->whereHas('keuzedeelInstance', function ($q) use ($keuzedeelId) {
                $q->where('keuzedeel_id', $keuzedeelId);
            })
            ->where('status', 'completed')
            ->exists();
    }

    /**
     * Check if user is enrolled in a period
     */
    public function isEnrolledInPeriod(int $periodId): bool
    {
        return $this->enrollments()
            ->whereHas('keuzedeelInstance', function ($q) use ($periodId) {
                $q->where('period_id', $periodId);
            })
            ->whereIn('status', ['enrolled'])
            ->exists();
    }

    /**
     * Get completed keuzedelen IDs
     */
    public function getCompletedKeuzedeelIds(): array
    {
        return $this->enrollments()
            ->with('keuzedeelInstance')
            ->where('status', 'completed')
            ->get()
            ->pluck('keuzedeelInstance.keuzedeel_id')
            ->unique()
            ->toArray();
    }
}
