<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Keuzedeel extends Model
{
    use HasFactory;

    protected $table = 'keuzedelen';

    protected $fillable = [
        'name',
        'slug',
        'code',
        'short_description',
        'content',
        'is_repeatable',
        'is_active',
        'max_students',
        'min_students',
        'credits',
        'image',
    ];

    protected $casts = [
        'is_repeatable' => 'boolean',
        'is_active' => 'boolean',
        'max_students' => 'integer',
        'min_students' => 'integer',
        'credits' => 'integer',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($keuzedeel) {
            if (empty($keuzedeel->slug)) {
                $keuzedeel->slug = Str::slug($keuzedeel->name);
            }
        });
    }

    /**
     * Instances of this keuzedeel across periods
     */
    public function instances(): HasMany
    {
        return $this->hasMany(KeuzedeelInstance::class);
    }

    /**
     * Programs that can access this keuzedeel
     */
    public function programs(): BelongsToMany
    {
        return $this->belongsToMany(Program::class, 'keuzedeel_program')
            ->withTimestamps();
    }

    /**
     * Scope for active keuzedelen
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for keuzedelen available to a specific program
     */
    public function scopeForProgram($query, $programId)
    {
        return $query->whereHas('programs', function ($q) use ($programId) {
            $q->where('programs.id', $programId);
        });
    }

    /**
     * Get route key name for URL binding
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
