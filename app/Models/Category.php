<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Allow mass assignment for these columns
    protected $fillable = ['name', 'slug', 'min_price', 'max_price'];

    // A Category has many Courses
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
