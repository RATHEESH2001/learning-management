<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'slug',
        'summary',
        'position',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    // Relationships
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Optional: scope for published modules
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('position');
    }
}
