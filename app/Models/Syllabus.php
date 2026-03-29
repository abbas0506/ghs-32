<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Syllabus extends Model
{
    use HasFactory;
    protected $fillable = [
        'subject_id',
        'grade_id',
        'term1',
        'term2',
        'term3',
    ];

    public function scopeForGrade($query, $gradeId)
    {
        return $query->where('grade_id', $gradeId);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    public function  completionPercentage(): int
    {
        $percentage = 0;
        if ($this->term1 && strlen($this->term1) > 1) $percentage += 33;
        if ($this->term2 && strlen($this->term2) > 1) $percentage += 33;
        if ($this->term3 && strlen($this->term3) > 1) $percentage += 33;
        if ($percentage == 99) $percentage = 100;
        return $percentage;
    }
}
