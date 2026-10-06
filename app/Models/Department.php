<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}