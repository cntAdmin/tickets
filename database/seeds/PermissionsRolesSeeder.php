<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ROL ADMIN
        $admin_role = Role::find(2);
        $admin_role->givePermissionTo([
            'users.view', 'users.create', 'users.update', 'users.delete',
            'departments.view', 'departments.create', 'departments.update', 'departments.delete',
            'customers.view', 'customers.create', 'customers.update', 'customers.delete',
            'tickets.view', 'tickets.create', 'tickets.update', 'tickets.delete',
        ]);

        // ROL DEPARTMENT
        $department_role = Role::find(3);
        $department_role->givePermissionTo([
            'users.view', 'users.create', 'users.update', 'users.delete',
            'departments.view', 'departments.create', 'departments.update', 'departments.delete',
            'customers.view', 'customers.create', 'customers.update', 'customers.delete',
            'tickets.view', 'tickets.create', 'tickets.update', 'tickets.delete',
        ]);

        // ROL STAFF
        $staff_role = Role::find(4);
        $staff_role->givePermissionTo([
            'users.view', 'users.create', 'users.update', 'users.delete',
            'departments.view', 'departments.create', 'departments.update', 'departments.delete',
            'customers.view', 'customers.create', 'customers.update', 'customers.delete',
            'tickets.view', 'tickets.create', 'tickets.update', 'tickets.delete',
        ]);

        // ROL USER
        $user_role = Role::find(5);
        $user_role->givePermissionTo(['users.view', 'users.update', 'departments.view',
            'tickets.view', 'tickets.create', 'tickets.update', 'tickets.delete',
        ]);            
    }
}
