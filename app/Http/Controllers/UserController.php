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
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    private $messages = [
        'exists' => ':attribute no existe en la tabla.',
        'max' => ':attribute no puede superar :max caracteres',
        'string' => ':attribute debe ser tipo texto',
        'email' => ':attribute debe tener un formato correo electrónico',
        'numeric' => ':attribute debe ser un número',
        'boolean' => ':attribute es un campo boleano',
        'required' => ':attribute es un campo requerido',
        'required_without' => ':attribute es un campo requerido si :name no existe',
        'department_id.required_without' => ':attribute es un campo requerido si el rol no es admin, departamento o agente.',
        'customer_id.required_without' => ':attribute es un campo requerido si el rol no es cliente o contacto.'
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
        $users = User::role([2,3,4,5,6])->filterUsers()
            ->with(['customer', 'department', 'roles'])
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
        // Validación de datos
        $validator = Validator::make($req->all(), [
            'customer_id' => ['required_without:department_id', 'nullable', 'exists:customers,id'],
            'department_id' => ['required_without:customer_id', 'nullable', 'exists:departments,id'],
            'name' => ['required', 'string', 'max:100'],
            'surname' => ['nullable', 'string', 'max: 100'],
            'username' => ['required', 'string', 'max:100', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'numeric'],
            'password' => ['required', 'string', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
        ], $this->messages);

        if($validator->fails()) {
            return response()->json([
                'error' => true,
                'errors' => $validator->errors()
            ]);
        }

        try {
            $user = User::create([
                'name' => $req->name,
                'surname' => $req->surname,
                'username' => $req->username,
                'email' => $req->email,
                'phone' => $req->phone,
                'password' => Hash::make($req->password),
                // ? SE TIENE QUE VALIDAR EL EMAIL ?
                'email_verified_at' => now()->toDateTimeString(),
                'is_active' => true
            ]);
        } catch (\Throwable $th) {
            return response()->json($th);
        }

        
        if($req->customer_id) {
            $get_customer = Customer::find($req->customer_id);
            $user->customer()->associate($get_customer->id);
        } else {
            $get_department = Department::find($req->department_id);
            $user->department()->associate($get_department->id);
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
        // Validación de datos
        $validator = Validator::make($req->all(), [
            'customer_id' => ['required_without:department_id', 'nullable', 'exists:customers,id'],
            'department_id' => ['required_without:customer_id', 'nullable', 'exists:departments,id'],
            'name' => ['required', 'string', 'max:100'],
            'surname' => ['nullable', 'string', 'max: 100'],
            'username' => ['required', 'max:100', 'unique:users,username,' . $user->id],
            'email' => ['nullable', 'sometimes', 'email'],
            'phone' => ['required', 'numeric'],
            'password' => ['nullable', 'string', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
            'role_id' => ['sometimes', 'numeric', 'exists:roles,id'],
        ], $this->messages);

        if($validator->fails()) {
            return response()->json([
                'error' => true,
                'errors' => $validator->errors()
            ]);
        }
        // Actualización del usuario
        $user->update([
            'name' => $req->name,
            'surname' => $req->surname,
            'username' => $req->username,
            'email' => $req->email,
            'phone' => $req->phone,
            'password' => $req->password ? Hash::make($req->password) :  $user->password,
            'is_active' => $req->is_active,
        ]);

        // Busqueda del departamento en caso de venir completado y asignarselo al usuario
        if($req->customer_id) {
            $get_customer = Customer::find($req->customer_id);
            $user->customer()->associate($get_customer->id);
        } else {
            $get_department = Department::find($req->department_id);
            $user->department()->associate($get_department->id);
        }
        if($req->role_id) {
            $role = Role::find($req->role_id);
            $user->syncRoles($role);
        }
        $user->save();

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
