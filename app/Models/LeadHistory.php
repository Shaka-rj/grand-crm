<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadHistory extends Model
{
    protected $fillable = [
        'lead_id',
        'status_id',
        'user_id',
        'comment',
    ];

    public function status()
    {
        return $this->belongsTo(LeadStatus::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}