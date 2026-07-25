<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //admin
        $user = User::create([
            'email' => 'abbas.sscs@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $user->assignRole(['head']);
        $user->assignRole(['admin']);

        Profile::create([

            'user_id' => $user->id,
            'name' => 'Muhammad Abbas',
            'short_name' => 'Abbas',
            'father_name' => 'Muhammad Yousaf',
            'cnic' => '3530119663433',
            'dob' => '06/05/1978',
            'blood_group' => 'B+',
            'address' => 'PTCL Exchange Road, Depalpur',
            'phone' => '03000373004',
            'joined_at' => '10/16/2025',
            'designation' => 'Sr. Headmaster',
            'qualification' => 'MS in Computer Sc.',
            'bps' => 18,
            'personal_no' => '31282674',
            'seniority' => 1,

        ]);

        $user = User::create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
        ]);
        $user->assignRole(['admin']);
        Profile::create([
            'user_id' => $user->id,
            'name' => 'System Admin',
            'short_name' => 'Admin',
            'bps' => 18,
        ]);
    }
}
