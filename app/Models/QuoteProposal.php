<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;


class QuoteProposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'estimated_hours',
        'estimated_weight',
        'quantity',
        'price',
        'notes'
    ];


    // 'quote_request_id',handled in actions
    // 'material_id',handled in actions
    // user_id given by action
    // accept_token to be generated once the proposal changes status to 'sent'
    // accepted_at fills when client accepts
    // status controlled by action

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function quoteRequest(): BelongsTo
    {
        return $this->belongsTo(QuoteRequest::class);
    }
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }
    public function job(): HasOne
    {
        return $this->hasOne(Job::class);
    }
}
