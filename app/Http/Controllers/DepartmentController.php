<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    public function index(Request $req)
    {
        if(!$req->ajax()) {
            return view('departments.index');
        }

        $departments = Department::
        // SI ENCUENTRA "NAME" EN EL REQUEST FILTRA POR EL "NAME"
        when($req->name, function(Builder $q, $name) {
            $q->where('name', 'LIKE', $name . '%');
        })
        // SI ENCUENTRA "CODE" EN EL REQUEST FILTRA POR EL "CODE"
        ->when($req->code, function(Builder $q, $code) {
            $q->where('code', 'LIKE', $code . '%');
        })
        // EAGER LOAD USERS COUNT
        ->withCount('users')
        // ORDENA POR NOMBRE ASCENDENTE (A - Z)
        ->orderBy('name', 'ASC')
        // PAGINADO
        ->paginate();

        return response()->json([
            'success' => true,
            'departments' => $departments
        ]);
        
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $req)
    {
        $messages = [
            'required' => __('El :attribute es un campo obligatorio'),
            'string' => __('El :attribute debe ser una cadena de texto'),
            'max' => __('El :attribute no puede superar los :max caracteres'),
            'unique' => __('El :attribute debe ser un campo único en la tabla')
        ];

        $validator = Validator::make($req->all(), [
            'name' => ['required', 'string', 'max:100', 'unique:departments,name'],
            'code' => ['required', 'string', 'max:5', 'unique:departments,code']
        ], $messages);

        if($validator->fails()) {
            return response()->json([
                'error' => true,
                'errors' => $validator->errors()
            ]);
        }

        $new_dep = Department::create([
            'name' => $req->name,
            'code' => $req->code
        ]);

        return $new_dep
            ? response()->json([ 'success' => true, 'msg' => __('Departamento creado correctamente.') ])
            : response()->json([ 'error' => true, 'msg' => __('Lo sentimos, algo ha ido mal, inténtelo de nuevo mas tarde') ]);

    }

    public function edit(Department $department)
    {
        $this->authorize('department.update');
        
        return view('department.edit');
    }

    public function update(Request $req, Department $department)
    {
        $messages = [
            'required' => __('El :attribute es un campo obligatorio'),
            'string' => __('El :attribute debe ser una cadena de texto'),
            'max' => __('El :attribute no puede superar los :max caracteres'),
            'unique' => __('El :attribute debe ser un campo único en la tabla')
        ];

        $validator = Validator::make($req->all(), [
            'name' => ['required', 'string', 'max:100', 'unique:departments,name,' . $department->id],
            'code' => ['required', 'string', 'max:5', 'unique:departments,code,' . $department->id]
        ], $messages);

        if($validator->fails()) {
            return response()->json([
                'error' => true,
                'errors' => $validator->errors()
            ]);
        }

        $updated = $department->update([
            'name' => $req->name,
            'code' => $req->code
        ]);

        return $updated
            ? response()->json([ 'success' => true, 'msg' => __('Departamento actualizado correctamente.') ])
            : response()->json([ 'error' => true, 'msg' =>  __('Lo sentimos, algo ha ido mal, inténtelo de nuevo mas tarde') ]);

    }

    public function destroy(Department $department)
    {
        $this->authorize('departments.destroy');
        $dep_users = User::where('department_id', $department->id)->each(function($user) {
            $user->department()->dissociate();
            $user->save();
        });

        $deleted = $department->delete();

        return $deleted
        ? response()->json([ 'success' => true, 'msg' => __('Departamento eliminado correctamente.') ])
        : response()->json([ 'error' => true, 'msg' => __('Lo sentimos, algo ha ido mal, inténtelo de nuevo mas tarde') ]);
    }

    public function get_all_departments() 
    {
        return response()->json([
            'success' => true,
            'departments' => \App\Models\Department::all()->toArray()
        ]);
    }

    public function get_department_users(Request $req) 
    {
        $users = User::whereNull('customer_id')
            ->when($req->name, function(Builder $q, $name) {
                $q->where('name', 'LIKE', '%' . $name . '%');
            })->when($req->surname, function(Builder $q, $surname) {
                $q->where('surname', 'LIKE', '%' . $surname . '%');
            })->when($req->email, function(Builder $q, $email) {
                $q->where('email', 'LIKE', '%' . $email . '%');
            })->when($req->phone, function(Builder $q, $phone) {
                $q->where('phone', 'LIKE', $phone . '%');
        })->paginate();

        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }

    public function assign_user(Department $department, User $user) 
    {
        $user->department()->associate($department);
        $user->save();

        return response()->json([
            'success' => true,
            'msg' => 'Usuario asignado correctamente',
            'color' => 'success'
        ]) ;
    }

    public function unassign_user(Department $department, User $user) 
    {
        $user->department()->dissociate($department);
        $user->save();

        return response()->json([
            'success' => true,
            'msg' => 'Usuario desasignado correctamente',
            'color' => 'danger'
        ]) ;
    }
}
