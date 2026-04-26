<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class QuoteRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'customer_email',
        'title',
        'description',
        'quantity'
    ];

    // user_id: handled in actions
    // customer_id: handled in actions 
    // stl_file_path: Given by STL parser
    // stl_volume_mm3, stl_dim_x/y/z: Given by STL parser
    // estimated_price: calculated via service
    // status: changes via action, defaults as pending 

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function quoteProposal(): HasOne
    {
        return $this->hasOne(QuoteProposal::class);
    }
}
