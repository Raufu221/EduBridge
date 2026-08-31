<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id', 'title', 'content', 'transcript', 'type', 
        'video_url', 'video_path', 'duration', 'order', 'is_preview',
        'resource_file', 'resource_name'
    ];

    protected $casts = [
        'is_preview' => 'boolean',
    ];

    // A Lesson belongs to a Module
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function quiz()
    {
        return $this->hasOne(Quiz::class);
    }

    public function assignment()
    {
        return $this->hasOne(Assignment::class);
    }

    // A Lesson can be completed by many Users
    public function completors()
    {
        return $this->belongsToMany(User::class, 'lesson_user')->withPivot('completed_at')->withTimestamps();
    }
}