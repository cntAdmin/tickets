<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Customer;
use App\Models\Department;
use App\Exports\UserExport;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;

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

    public function index(Request $req)
    {
        if(!$req->ajax()) {
            return abort(404);
        }
        // USUARIOS QUE EL ROL NO SEA SUPERADMIN
        $users = User::filterUsers()
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

    public function create()
    {
        return view('users.create');
        
    }

    public function store(Request $req)
    {
        // Validación de datos
        $validator = Validator::make($req->all(), [
            'customer_id' => ['required_without:department_id', 'nullable', 'exists:customers,id'],
            'department_id' => ['required_without:customer_id', 'nullable', 'exists:departments,id'],
            'role_id' => ['required', 'numeric', 'exists:roles,id'],
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

        $get_role = Role::find($req->role_id);
        $user->syncRoles($get_role);

        $user->save();

        return response()->json([
            'success' => true,
            'msg' => __('Usuario creado correctamente')
        ]);
    }

    public function show(User $user)
    {
        if($user->getRoleNames()[0] === "customer") {
            $contacts = Customer::find($user->customer_id)->contacts();
        }

        return response()->json([
            'success' => true,
            'user' => $user->load(['roles', 'customer', 'department']),
            'contacts' => $contacts ?? '',
        ]);

    }

    public function edit(User $user)
    {
        $this->authorize('users.update');

        return view('users.edit', compact($user));
    }

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

    public function get_all_users(Request $req) 
    {
        $users = User::where('is_active', 1)->when($req->customer_id, function(Builder $q, $customer_id){
            $q->where('customer_id', $customer_id);
        })->get()->toArray();

        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }

    public function get_users_counters() 
    {
        $superadmin_count = User::role(1)->count();
        $admin_count = User::role(2)->count();
        $department_count = User::role(3)->count();
        $staff_count = User::role(4)->count();
        $customer_count = User::role(5)->count();
        $contact_count = User::role(6)->count();

        return response()->json([
            'admin_count' => $admin_count + $superadmin_count,
            'staff_count' => $staff_count,
            'department_count' => $department_count,
            'customer_count' => $customer_count,
            'contact_count' => $contact_count
        ]);
    }

    public function export_users(Request $req) 
    {
        switch ($req->type) {
            case 'excel':
                if (!Storage::exists('exports/excels')) {
                    Storage::makeDirectory('exports/excels');
                }
                
                $filename = 'users-' . now()->toDateTimeString() . '.xlsx';
                $storage = 'exports/excels/' . $filename;
                Excel::store(new UserExport($req), $storage);
                break;
            case 'pdf':
                if (!Storage::exists('exports/pdfs')) {
                    Storage::makeDirectory('exports/pdfs');
                }
                $filename = 'users-' . now()->format('Y-m-d_H-i-s') . '.pdf';
                $storage = 'exports/pdfs/' . $filename;
                $users = User::filterUsers()->get();
                $pdf = PDF::loadView('exports.users', ['users' => $users])
                    ->setPaper('a4', 'landscape')
                    ->setOptions([
                        'defaultFont' => 'sans-serif',
                        'debugCss' => true
                    ]);
                Storage::put($storage, $pdf->output());
                break;
        }
        $headers = [
            'Content-Type' => 'application/*',
        ];

        return response()->download(Storage::path($storage), $filename, $headers);
    }

    public function toggle_active_user(User $user, Request $req)
    {
        $updated = $user->update([
            'is_active' => $req->is_active
        ]);
        $status = $req->is_active ? "activado" : "desactivado";
        
        return $updated
            ? response()->json([ 'success' => true, 'msg' => 'Usuario ' . $status  . ' correctamente.'])
            : response()->json([ 'success' => true, 'msg' => 'No ha podido ser '. $status . ' correctamente']);
    }

    // Usuarios agentes a los que se les asignarán las incidencias (Metemos también Admin)
    public function get_all_agents()
    {
        $agents = User::role(['Agente','Admin'])
            ->where('id','!=', 214) // Juan Jose - Jefe
            ->where('id','!=', 565) // Juanjo Rodaex - Jefe
            ->get()->toArray();

        return response()->json([
            'agents' => $agents,
        ]);
    }
}
