<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Testing\Fluent\Concerns\Has;

class Material extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'density',
        'cost_per_gram'
    ];
    // user_id handled in actions
    // defaulted_at handled in actions

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function quoteProposals(): HasMany
    {
        return $this->hasMany(QuoteProposal::class);
    }
}
