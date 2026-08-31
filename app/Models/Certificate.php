<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'user_id', 'course_id', 'certificate_code', 'full_name', 'average_score', 'is_valid', 'issue_date'
    ];

    protected $casts = [
        'is_valid' => 'boolean',
        'issue_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
