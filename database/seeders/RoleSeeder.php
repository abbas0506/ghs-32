<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'head']);
        Role::create(['name' => 'teacher']);
        Role::create(['name' => 'librarian']);
        Role::create(['name' => 'jc']);
        Role::create(['name' => 'c4']);
    }
}
