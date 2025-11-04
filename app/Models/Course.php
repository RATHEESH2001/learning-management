<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['instructor_id','title','slug','description','is_published','price'];

    public function instructor() { return $this->belongsTo(User::class, 'instructor_id'); }
    public function modules() { return $this->hasMany(Module::class)->orderBy('position'); }


    protected $casts = [
    'is_published' => 'boolean',
    'price' => 'decimal:2',
];

public function getThumbnailUrlAttribute()
{
    return $this->thumbnail ? asset('storage/'.$this->thumbnail) : null;
}

}
