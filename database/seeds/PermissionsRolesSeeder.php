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
            'users.view', 'users.create', 'users.update', 'users.destroy',
            'departments.view', 'departments.create', 'departments.update', 'departments.destroy',
            'customers.view', 'customers.create', 'customers.update', 'customers.destroy',
            'contacts.view', 'contacts.create', 'contacts.update', 'contacts.destroy',
            'tickets.view', 'tickets.create', 'tickets.update', 'tickets.destroy',
        ]);

        // ROL DEPARTMENT
        $department_role = Role::find(3);
        $department_role->givePermissionTo([
            'users.view', 'users.create', 'users.update', 'users.destroy',
            'departments.view', 'departments.create', 'departments.update', 'departments.destroy',
            'customers.view', 'customers.create', 'customers.update', 'customers.destroy',
            'contacts.view', 'contacts.create', 'contacts.update', 'contacts.destroy',
            'tickets.view', 'tickets.create', 'tickets.update', 'tickets.destroy',
        ]);

        // ROL STAFF
        $staff_role = Role::find(4);
        $staff_role->givePermissionTo([
            'users.view', 'users.create', 'users.update', 'users.destroy',
            'departments.view', 'departments.create', 'departments.update', 'departments.destroy',
            'customers.view', 'customers.create', 'customers.update', 'customers.destroy',
            'contacts.view', 'contacts.create', 'contacts.update', 'contacts.destroy',
            'tickets.view', 'tickets.create', 'tickets.update', 'tickets.destroy',
        ]);
        
        // ROL CUSTOMER
        $customer_role = Role::find(5);
        $customer_role->givePermissionTo(['users.view', 'users.update', 'departments.view',
            'tickets.view', 'tickets.create', 'tickets.update', 'tickets.destroy',
        ]);

        // ROL USER
        $user_role = Role::find(6);
        $user_role->givePermissionTo(['users.view', 'users.update', 'departments.view',
            'tickets.view', 'tickets.create', 'tickets.update', 'tickets.destroy',
        ]);
    }
}
