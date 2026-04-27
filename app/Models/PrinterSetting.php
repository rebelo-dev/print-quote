<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class PrinterSetting extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'cost_per_hour',
        'mm3_per_hour',
        'margin_percentage'
    ];
    //user_id handled in actions


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
