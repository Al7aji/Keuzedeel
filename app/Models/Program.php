<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Users (students) belonging to this program
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Keuzedelen available for this program
     */
    public function keuzedelen(): BelongsToMany
    {
        return $this->belongsToMany(Keuzedeel::class, 'keuzedeel_program')
            ->withTimestamps();
    }

    /**
     * Scope for active programs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
