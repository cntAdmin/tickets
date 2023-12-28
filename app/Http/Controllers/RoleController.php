<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function get_all_roles() 
    {
        $roles = Role::where('name', '<>', 'superadmin')->get()->toArray();

        return response()->json([
            'success' => true,
            'roles' => $roles
        ]);
    }
}
