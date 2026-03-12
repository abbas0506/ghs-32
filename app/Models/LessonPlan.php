<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonPlan extends Model
{
    use HasFactory;
    protected $fillable = [
        'subject_id',
        'grade_id',
        'day_no', //1,2,3,4,5,6
        'topic',
        'objective',
        'activity',
        'homework',
        'remarks',
    ];
}
