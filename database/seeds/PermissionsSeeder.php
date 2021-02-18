<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            // Permisos sobre la tabla "car_models"
            [
                'name' => 'car_models.view',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'car_models.create',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'car_models.update',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'car_models.destroy',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Permisos sobre la tabla "brands"
            [
                'name' => 'brands.view',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'brands.create',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'brands.update',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'brands.destroy',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Permisos sobre la tabla "users"
            [
                'name' => 'users.view',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'users.create',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'users.update',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'users.destroy',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Permisos sobre la tabla "contacts"
            [
                'name' => 'contacts.view',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'contacts.create',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'contacts.update',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'contacts.destroy',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Permisos sobre la tabla "departments"
            [
                'name' => 'departments.view',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'departments.create',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'departments.update',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'departments.change',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'departments.destroy',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Permisos sobre la tabla "customers"
            [
                'name' => 'customers.view',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'customers.create',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'customers.update',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'customers.destroy',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Permisos sobre la tabla "roles"
            [
                'name' => 'roles.view',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'roles.create',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'roles.update',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'roles.destroy',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Permisos sobre la tabla "permissions"
            [
                'name' => 'permissions.view',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'permissions.create',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'permissions.update',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'permissions.destroy',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Permisos sobre la tabla "tickets"
            [
                'name' => 'tickets.view',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'tickets.create',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'tickets.update',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'tickets.destroy',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Permisos sobre la tabla "engine_types"
            [
                'name' => 'engine_types.view',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'engine_types.create',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'engine_types.update',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'engine_types.destroy',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        Permission::insert($permissions);
    }
}
