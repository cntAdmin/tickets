<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        if($req->ajax()) {
            $users = User::
            // SI ENCUENTRA "NAME" EN EL REQUEST FILTRA POR EL "NAME"
            when($req->name, function(Builder $q, $name) {
                $q->where('name', 'LIKE', $name . '%');
            })
            // SI ENCUENTRA "SURNAME" EN EL REQUEST FILTRA POR EL "SURNAME"
            ->when($req->surname, function(Builder $q, $surname) {
                $q->where('surname', 'LIKE', $surname . '%');
            })
            // SI ENCUENTRA "EMAIL" EN EL REQUEST FILTRA POR EL "EMAIL"
            ->when($req->email, function(Builder $q, $email) {
                $q->where('email', 'LIKE', $email . '%');
            })
            // SI ENCUENTRA "PHONE" EN EL REQUEST FILTRA POR EL "PHONE"
            ->when($req->phone, function(Builder $q, $phone) {
                $q->where('phone', 'LIKE', $phone . '%');
            })
            // ORDENA POR APELLIDO INCREMENTAL (A - Z)
            ->orderBy('surname', 'ASC')
            // PAGINADO
            ->paginate();

            return response()->json([
                'users' => $users
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
        return view('users.create');
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, User $user)
    {
        // Mensajes de respuesta si falla alguna validación
        $messages = [
            'department_id.exists' => __('Lo sentimos pero el departamento no existe.'),
            'name.required' => __('Lo sentimos pero el nombre es un campo obligatorio.'),
            'name.max' => __('Su nombre es demasiado largo, si es real, póngase en contacto con nosotros.'),
            'surname.max' => __('Su apellido es demasiado largo, si es real, póngase en contacto con nosotros.'),
            'email.required' => __('La dirección de correo es obligatoria.'),
            'email.unique' => __('La dirección de correo ya existe, por favor utilice otra o póngase en contacto con el administrador.'),
            'phone.required' => __('El teléfono es obligatorio.'),
            'password.confirmed' => __('La contraseña no es igual en ambos casos.'),
            'is_active.boolean' => __('Este campo debe ser (true o false).'),
        ];

        // Validación de datos
        $validator = Validator::make($req->all(), [
            'department_id' => ['nullable', 'exists:departments,id'],
            'name' => ['required', 'string', 'max:100'],
            'surname' => ['nullable', 'string', 'max: 100'],
            'email' => ['required', 'sometimes', 'email', 'unique:users,email,' . $user],
            'phone' => ['required', 'numeric'],
            'password' => ['password', 'confirmed'],
            'is_active' => ['boolean'],
        ], $messages);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        // Actualización del usuario
        $user->update([
            'name' => $req->name,
            'surname' => $req->surname,
            'email' => $req->email,
            'phone' => $req->phone,
            'password' => Hash::make($req->password),
            'is_active' => $req->is_active ? true : false,
        ]);

        // Busqueda del departamento en caso de venir completado y asignarselo al usuario
        if($req->department_id) {
            $department = Department::find($req->department_id);
            $department->users()->save($user);
        }

        return response()->json([
            'success' => 'El usuario se ha actualizado correctamente'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('users.destroy');
        
        $user->delete();

        return response()->json([
            'success' => 'Usuario eliminado correctamente.'
        ]);
    }
}
