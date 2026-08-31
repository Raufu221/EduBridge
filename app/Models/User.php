<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'phone',
        'profile_pic',
        'about_me',
        'social_links',
        'notification_settings',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'notification_settings' => 'array',
        ];
    }
    // An Instructor can create many Courses
    public function courses()
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    public function instructorApplications()
    {
        return $this->hasMany(InstructorApplication::class);
    }

    // A User can have many Enrollments
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    // A User belongs to many Courses (as a student) through enrollments
    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'enrollments');
    }

    // A User can complete many lessons
    public function completedLessons()
    {
        return $this->belongsToMany(Lesson::class)->withPivot('completed_at')->withTimestamps();
    }

    // A User can join many waitlists
    public function waitlists()
    {
        return $this->hasMany(Waitlist::class);
    }

    // A User can leave many reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // A User can earn many certificates
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }
}
