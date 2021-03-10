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
        $roles = [
            [
                'name' => 'superadmin',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'name' => 'Admin',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'name' => 'Departamento',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'name' => 'Agente',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'name' => 'Cliente',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'name' => 'Contacto',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];
        
        Role::insert($roles);
        
    }
}
