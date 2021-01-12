<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles[] = [
            'name' => 'superadmin',
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $roles[] = [
            'name' => 'admin',
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $roles[] = [
            'name' => 'department',
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $roles[] = [
            'name' => 'staff',
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $roles[] = [
            'name' => 'user',
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        
        Role::insert($roles);
        
    }
}
