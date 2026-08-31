<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstructorApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'email',
        'phone',
        'linkedin',
        'expertise',
        'experience_years',
        'portfolio',
        'proposal_topic',
        'teaching_approach',
        'demo_video_url',
        'status',
        'admin_feedback',
        'bio',          // Keep old fields for compatibility during transition
        'portfolio_url' // Keep old fields for compatibility during transition
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
