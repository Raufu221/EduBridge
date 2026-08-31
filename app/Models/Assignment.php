<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    //
    protected $fillable = ['lesson_id', 'total_marks', 'passing_marks'];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
