<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Department;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $attributes = [
        'department_id' => 'Departamento',
        'name' => 'Nombre',
        'surname' => 'Apellidos',
        'email' => 'Email',
        'phone' => 'Teléfono',
        'password' => 'Contraseña',
        'password_confirmation' => 'Rep. Contraseña',
        'is_active' => 'Activo'
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        if(!$req->ajax()) {
            return abort(404);
        }
        // USUARIOS QUE EL ROL NO SEA SUPERADMIN
        $users = User::role([2,3,4,5,6])
        ->when($req->name, function(Builder $q, $name) {
            $q->where('name', 'LIKE', $name . '%');
        })->when($req->surname, function(Builder $q, $surname) {
            $q->where('surname', 'LIKE', $surname . '%');
        })->when($req->email, function(Builder $q, $email) {
            $q->where('email', 'LIKE', $email . '%');
        })->when($req->phone, function(Builder $q, $phone) {
            $q->where('phone', 'LIKE', $phone . '%');
        })->with(['customer', 'department', 'roles'])
        ->withCount('tickets')
        ->orderBy('surname', 'ASC')
        // PAGINADO
        ->paginate();

        return response()->json([
            'success' => true,
            'users' => $users
        ]);
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
    public function store(Request $req)
    {
        $messages = [
            'exists' => ':attribute no existe en la tabla.',
            'max' => ':attribute no puede superar :max caracteres',
            'string' => ':attribute debe ser tipo texto',
            'email' => ':attribute debe tener un formato correo electrónico',
            'numeric' => ':attribute debe ser un número',
            'boolean' => ':attribute es un campo boleano',
            'required' => ':attribute es un campo requerido',
        ];

        // Validación de datos
        $validator = Validator::make($req->all(), [
            'customer_id' => ['required_without:department_id', 'nullable', 'exists:customers,id'],
            'department_id' => ['required_without:customer_id', 'nullable', 'exists:departments,id'],
            'name' => ['required', 'string', 'max:100'],
            'surname' => ['nullable', 'string', 'max: 100'],
            'username' => ['required', 'max:100', 'unique:users,username'],
            'email' => ['nullable', 'sometimes', 'email'],
            'phone' => ['required', 'numeric'],
            'password' => ['required', 'string', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
        ], $messages, $this->attributes);

        if($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ]);
        }

        $user = User::create([
            'name' => $req->name,
            'surname' => $req->surname,
            'username' => $req->username,
            'email' => $req->email,
            'phone' => $req->phone,
            'password' => Hash::make($req->password),
            // ? SE TIENE QUE VALIDAR EL EMAIL ?
            'email_verified_at' => now(),
            'is_active' => 1
        ]);
        
        if($req->customer_id) {
            $get_customer = Customer::find($req->customer_id);
            $user->customer()->associate($get_customer);
        } else {
            $get_department = Department::find($req->department_id);
            $user->department()->associate($get_department);
        }

        $user->save();
        return response()->json([
            'success' => true,
            'msg' => __('Usuario creado correctamente')
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('users.show', compact($user));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->authorize('users.update');

        return view('users.edit', compact($user));
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
            'exists' => ':attribute no existe en la tabla.',
            'max' => ':attribute no puede superar :max caracteres',
            'string' => ':attribute debe ser tipo texto',
            'email' => ':attribute debe tener un formato correo electrónico',
            'numeric' => ':attribute debe ser un número',
            'boolean' => ':attribute es un campo boleano',
            'required' => ':attribute es un campo requerido',
        ];

        // Validación de datos
        $validator = Validator::make($req->all(), [
            // 'customer_id' => ['required_without:department_id', 'nullable', 'exists:customers,id'],
            // 'department_id' => ['required_without:customer_id', 'nullable', 'exists:departments,id'],
            'name' => ['required', 'string', 'max:100'],
            'surname' => ['nullable', 'string', 'max: 100'],
            'username' => ['required', 'max:100', 'unique:users,username,' . $user->id],
            'email' => ['nullable', 'sometimes', 'email'],
            'phone' => ['required', 'numeric'],
            'password' => ['nullable', 'string', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
        ], $messages, $this->attributes);

        if($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ]);
        }
        // Actualización del usuario
        $user->update([
            'name' => $req->name ? $req->name : $user->name,
            'surname' => $req->surname ? $req->surname : $user->surname,
            'username' => $req->username ? $req->username : $user->username,
            'email' => $req->email ? $req->email : $user->email,
            'phone' => $req->phone ? $req->phone : $user->phone,
            'password' => $req->password ? Hash::make($req->password) :  $user->password,
            'is_active' => $req->is_active ? 1 : 0,
        ]);

        // Busqueda del departamento en caso de venir completado y asignarselo al usuario
        if($req->department_id) {
            $department = Department::find($req->department_id);
            $department->users()->save($user);
        }

        return response()->json([
            'success' => true,
            'msg' => 'El usuario se ha actualizado correctamente'
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
        $tickets = Ticket::find($user->tickets->toArray());

        $tickets->each(function($ticket) {
            $ticket->user()->dissociate();
            $ticket->save();
        });

        $user->delete();

        return response()->json([
            'success' => true,
            'msg' => __('Usuario eliminado correctamente.')
        ]);
    }

    public function get_all_users(Request $req) {
        $users = User::where('is_active', 1)->when($req->customer_id, function(Builder $q, $customer_id){
            $q->where('customer_id', $customer_id);
        })->get()->toArray();

        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }

    public function get_users_counters() {
        $admin_count = User::role('admin')->count();
        $staff_count = User::role('staff')->count();
        $department_count = User::role('department')->count();
        $customer_count = User::role('customer')->count();
        $contact_count = User::role('contact')->count();

        return response()->json([
            'admin_count' => $admin_count,
            'staff_count' => $staff_count,
            'department_count' => $department_count,
            'customer_count' => $customer_count,
            'contact_count' => $contact_count
        ]);
    }
}
