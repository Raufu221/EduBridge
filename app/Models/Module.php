<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'title', 'order'];

    // A Module belongs to a Course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // A Module has many Lessons
    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order'); // Always load lessons in the correct order!
    }
}