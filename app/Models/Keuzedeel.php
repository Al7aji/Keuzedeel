<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keuzedeel extends Model
{
    protected $fillable = [
    'titel',
    'image',
    'code',
    'status',
    'description',
    ];
}
