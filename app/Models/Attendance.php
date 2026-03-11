<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'date',
        'status', //0 for absent
    ];
    protected $casts = [
        'date' => 'date',
    ];
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function scopeAbsences($query)
    {
        return $query->where('status', 0)->whereDate('date', '<', today());
    }
    public function scopePresences($query)
    {
        return $query->where('status', 1)->whereDate('date', '<=', today());
    }
}
