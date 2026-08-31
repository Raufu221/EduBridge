<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayoutRequest extends Model
{
    protected $fillable = [
        'instructor_id',
        'amount',
        'payout_method',
        'account_details',
        'status',
        'payout_trx_id',
        'processed_at',
        'admin_notes'
    ];

    protected $casts = [
        'processed_at' => 'datetime',
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
}
