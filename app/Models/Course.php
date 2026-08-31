<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    // We updated 'teacher_id' to 'instructor_id' and added the new columns!
    protected $fillable = [
        'instructor_id', 'category_id', 'title', 'slug', 'description', 
        'what_you_will_learn', 'requirements', 'target_audience', 'level',
        'price', 'max_students', 'cover_image', 'is_published', 'is_submitted',
        'admin_feedback'
    ];

    
    protected $casts = [
        'is_published' => 'boolean',
        'is_submitted' => 'boolean',
    ];

    // A Course belongs to an Instructor (User)
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    // A Course belongs to a Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function modules()
    {
        return $this->hasMany(Module::class)->orderBy('order');
    }

    // A Course can have many Enrollments
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }



    // A Course has many Students through Enrollments
    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments');
    }

    public function waitlists()
    {
        return $this->hasMany(Waitlist::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getAverageRatingAttribute()
    {
        return round($this->reviews()->avg('rating'), 1) ?: 0;
    }

    public function getReviewsCountAttribute()
    {
        return $this->reviews()->count();
    }

    /**
     * Handle both Slugs and IDs for public routing.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('slug', $value)
            ->orWhere('id', $value)
            ->firstOrFail();
    }
        /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}