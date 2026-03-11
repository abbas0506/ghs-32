<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'max_marks',
        'user_id',   //test owner id , blank if combined test

        'is_open',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function allocations()
    {
        return $this->belongsToMany(Allocation::class, 'test_allocations', 'test_id', 'allocation_id');
    }

    public function testAllocations()
    {
        return $this->hasMany(TestAllocation::class);
    }

    public function scopeOpen($query)
    {
        return $query->where('is_open', true);
    }
    public function scopeClosed($query)
    {
        return $query->where('is_open', false);
    }
    public function scopeCombined($query)
    {
        return $query->whereNull('user_id');
    }
    public function scopeIndividual($query)
    {
        return $query->whereNotNull('user_id');
    }
    public function scopeAccessible($query)
    {

        if (Auth::user()->hasRole('teacher')) {
            return  $query->whereHas('testAllocations', function ($q) {
                $q->where('user_id', Auth::user()->id);
            });
        }
        return $query;
    }
    public function scopeMine($query)
    {
        if (Auth::user()->hasAnyRole('admin|head')) {
            return $query;
        }
        return $query->open()->whereHas('testAllocations', function ($q) {
            $q->where('user_id', Auth::user()->id);
        });
    }
}
