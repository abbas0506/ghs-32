<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Account;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            GradeSeeder::class,
            SubjectSeeder::class,
            GradeSubjectSeeder::class,
            LectureTimingSeeder::class,
            AccountSeeder::class,
            BookDomainSeeder::class,
            BookReturnPolicySeeder::class,
        ]);
    }
}
