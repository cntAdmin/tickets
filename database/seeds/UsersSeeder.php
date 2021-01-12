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
        factory(App\Models\User::class, 100)->create()
        ->each(function (App\Models\User $user, $idx) {
            // Asignamos un rol por usuario
            switch ($idx) {
                case '1':
                    $user->assignRole(Role::find(1));
                    break;
                case '2':
                    $user->assignRole(Role::find(2));
                    break;
                case '3':
                    $user->assignRole(Role::find(3));
                    break;
                case '4':
                    $user->assignRole(Role::find(4));
                    break;
                
                default:
                    $user->assignRole(Role::find(5));
                    break;
            }
            
        });
    }
}
