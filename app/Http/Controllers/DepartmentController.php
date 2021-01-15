<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        if($req->ajax()) {
            $departments = Department::
            // SI ENCUENTRA "NAME" EN EL REQUEST FILTRA POR EL "NAME"
            when($req->name, function(Builder $q, $name) {
                $q->where('name', 'LIKE', $name . '%');
            })
            // EAGER LOAD USERS COUNT
            ->withCount('users')
            // ORDENA POR NOMBRE INCREMENTAL (A - Z)
            ->orderBy('name', 'ASC')
            // PAGINADO
            ->paginate();

            return response()->json([
                'departments' => $departments
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('departments.create');
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        $messages = [
            'name.required' => __('El nombre es un campo obligatorio'),
            'name.string' => __('Debe ser una cadena de texto'),
            'name.max' => __('No puede superar los :max caracteres'),
            'name.unique' => __('Debe ser un campo único en la tabla')
        ];

        $validator = Validator::make($req->all(), [
            'name' => ['required', 'string', 'max:100', 'unique:departments,name']
        ], $messages);

        if($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ]);
        }

        $new_dep = Department::create([
            'name' => $req->name
        ]);

        return $new_dep
            ? response()->json([ 'success' => __('Departamento creado correctamente.'), 'new_dep' => $new_dep ])
            : response()->json([ 'error' => __('Lo sentimos, algo ha ido mal, inténtelo de nuevo mas tarde') ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        $this->authorize('department.update');
        
        return view('department.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, Department $department)
    {
        $messages = [
            'name.required' => __('El nombre es un campo obligatorio'),
            'name.string' => __('Debe ser una cadena de texto'),
            'name.max' => __('No puede superar los :max caracteres'),
            'name.unique' => __('Debe ser un campo único en la tabla')
        ];

        $validator = Validator::make($req->all(), [
            'name' => ['required', 'string', 'max:100', 'unique:departments,name,' . $department]
        ], $messages);

        if($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ]);
        }

        $updated = $department->update([
            'name' => $req->name
        ]);

        return $updated
            ? response()->json([ 'success' => __('Departamento actualizado correctamente.'), 'updated' => $updated ])
            : response()->json([ 'error' => __('Lo sentimos, algo ha ido mal, inténtelo de nuevo mas tarde') ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        $this->authorize('departments.destroy');

        $deleted = $department->delete();

        return $deleted
        ? response()->json([ 'success' => __('Departamento eliminado correctamente.'), 'deleted' => $deleted ])
        : response()->json([ 'error' => __('Lo sentimos, algo ha ido mal, inténtelo de nuevo mas tarde') ]);
    }

    public function get_all_departments() {
        return response()->json(\App\Models\Department::all());
    }
}
