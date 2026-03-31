<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // 'user_id',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // 'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id');
    }
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function salaries()
    {
        return $this->hasMany(Salary::class);
    }
    public function tests()
    {
        return $this->hasMany(Test::class);
    }
    public function testSubjects()
    {
        return $this->belongsToMany(TestSubject::class);
    }
    public function isIncharge()
    {
        return $this->allocations->where('lecture_no', 1)->count();
    }
    public function accessibleSections()
    {

        if (session('role') == 'admin' || session('role') == 'head') {
            return Section::all();
        } else if (session('role') == 'teacher') {
            return Section::whereIn('id', function ($query) {
                $query->select('section_id')
                    ->from('schedules')
                    ->where('user_id', $this->id)
                    ->where('lecture_no', 1);
            })->get();
        }

        return collect();
    }
    public function accessibleTests()
    {
        if ($this->hasAnyRole(['head', 'admin'])) {
            return  Test::all();
        }
        if ($this->hasRole('teacher')) {
            return  Test::whereHas('testSubjects', function ($query) {
                $query->where('user_id', $this->id);
            })->get();
        }

        return collect();
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class)
            ->withPivot('status')
            ->withTimestamps();
    }
    public function taskLines()
    {
        return $this->hasMany(TaskLine::class);
    }
}
