<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyReportValue extends Model
{
    protected $fillable = [
        'daily_report_id',
        'daily_report_field_id',
        'value',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(DailyReport::class, 'daily_report_id');
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(
            DailyReportField::class,
            'daily_report_field_id'
        )->withTrashed();
    }
}