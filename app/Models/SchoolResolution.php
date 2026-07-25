<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolResolution extends Model
{
    use HasFactory;

    protected $table = 'school_resolutions';

    protected $fillable = [
        'number',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'school_resolution_id');
    }
}
