<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::all()->each(function($user){
            $user->assignRole(6);
        });
        $users = [
            [
                'name' => 'Super',
                'surname' => 'Admin',
                'username' => 'superadmin',
                'phone' => '123456879',
                'email' => 'super@example.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
                'is_active' => rand(0,1),
                'created_at' => now(),
                'updated_at' => now()
            ],[
                'name' => 'Admin',
                'surname' => 'Solo',
                'username' => 'admin',
                'phone' => '123456879',
                'email' => 'admin@example.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
                'is_active' => rand(0,1),
                'created_at' => now(),
                'updated_at' => now()
            ],[
                'name' => 'Department',
                'surname' => 'Manager',
                'username' => 'department',
                'phone' => '123456879',
                'email' => 'department@example.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
                'is_active' => rand(0,1),
                'created_at' => now(),
                'updated_at' => now()
            ],[
                'name' => 'Staff',
                'surname' => '',
                'username' => 'staff',
                'phone' => '123456879',
                'email' => 'staff@example.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
                'is_active' => rand(0,1),
                'created_at' => now(),
                'updated_at' => now()
            ],[
                'name' => 'Customer',
                'surname' => '',
                'username' => 'customer',
                'phone' => '123456879',
                'email' => 'customer@example.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
                'is_active' => rand(0,1),
                'created_at' => now(),
                'updated_at' => now()
            ],[
                'name' => 'Contact',
                'surname' => '',
                'username' => 'contact',
                'phone' => '123456879',
                'email' => 'contact@example.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
                'is_active' => rand(0,1),
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        \App\Models\User::insert($users);
        $users = \App\Models\User::where('id', '>=', 144)->limit(6)->get();
            // factory(\App\Models\User::class, 6)->create()
        $users->each(function (\App\Models\User $user, $idx) {
                // Asignamos un rol por usuario
                switch ($idx) {
                    case '0': // Super
                        $user->assignRole(Role::find(1));
                        break;
                    case '1': // Admin
                        $user->assignRole(Role::find(2));
                        break;
                    case '2': // Department
                        $user->assignRole(Role::find(3));
                        break;
                    case '3': // Staff
                        $user->assignRole(Role::find(4));
                        break;
                    case '4': // Customer (admin de customers)
                        $user->assignRole(Role::find(5));
                        break;
                    case '5': // Contact
                        $user->assignRole(Role::find(6));
                        break;
                }
        });
    }
}
