<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'grade_no',
        'name',
    ];
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'grade_subjects', 'grade_id', 'subject_id');
    }
    public function sections()
    {
        return $this->hasMany(Section::class);
    }
    public function syllabi()
    {
        return $this->hasMany(Syllabus::class);
    }
}
