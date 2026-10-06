<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeadStatus extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'color',
        'icon'
    ];

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'status_id');
    }
}