<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function taskLines()
    {
        return $this->hasMany(TaskLine::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'task_lines')
            ->withPivot('status')
            ->withTimestamps();
    }
    public function isOpen()
    {
        if ($this->due_date->gte(today()))
            return true;
        else
            return false;
    }
}
