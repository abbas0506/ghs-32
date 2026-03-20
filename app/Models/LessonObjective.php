<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonObjective extends Model
{
    use HasFactory;
    protected $fillable = [
        'lesson_id',
        'objective',
    ];

    public function  lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
