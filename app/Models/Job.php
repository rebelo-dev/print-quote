<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Job extends Model
{
    use HasFactory, HasUuids;


    protected $fillable = [
        'title',             // inherited from quote_proposal via action
        'description',       // inherited from quote proposal via action
        'quantity',          // inherited from quote proposal via action
        'price',             // inherited from quote proposal via action
        'status',            // updated by printer: pending, printing, done, delivered
        'payment_status',    // aupdated by printer: pending→paid
        'payment_method',    // indicated by printer as a registry, not processed by printquote
        'started_at',        // filled when status is printing
        'completed_at',      // filled when status is done or delivered (maybe delivered as then the job is completed)
    ];

    //  'quote_proposal_id' handled in actions
    // user_id handled in actions




    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function quoteProposal(): BelongsTo
    {
        return $this->belongsTo(QuoteProposal::class);
    }
}
