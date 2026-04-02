<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LectureTiming extends Model
{
    use HasFactory;
    protected $fillable = [
        'lecture_no',
        'starts_at',
        'duration',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
    ];

    public function timeInterval($lectureNo)
    {
        // return time interval for given lecture_no
        $lecture = $this->where('lecture_no', $lectureNo)->first();
        if ($lecture) {
            return $lecture->starts_at->format('H:i') . "-" . $lecture->starts_at->addMinutes($lecture->duration)->format('H:i');
        }
        return '';
    }
}
