<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'course_id', 'coupon_id', 'gross_amount', 'discount_amount', 
        'net_paid', 'commission_amount', 'instructor_amount', 'payment_method', 
        'gateway_ref', 'sender_phone', 'manual_trx_id', 'status', 'clearance_date'
    ];

    protected $casts = [
        'clearance_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
