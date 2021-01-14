<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Department;
use App\Models\EngineType;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        if(!$req->ajax()) {
            return view('tickets.index')->with([
                'ticket_statuses' => TicketStatus::all()
            ]);
        }

// return $req->department;

        $tickets = Ticket::when($req->id, function(Builder $q, $id) {
                $q->where('id', 'LIKE', $id . '%');
            })->when($req->frame_id, function(Builder $q, $frame_id) {
                $q->where('frame_id', 'LIKE', $frame_id . '%');
            })->when($req->plate, function(Builder $q, $plate) {
                $q->where('plate', 'LIKE', $plate . '%');
            })->when($req->brand, function(Builder $q, $brand) {
                $q->where('brand', 'LIKE', $brand . '%');
            })->when($req->model, function(Builder $q, $model) {
                $q->where('model', 'LIKE', $model . '%');
            })->when($req->engine_type, function(Builder $q, $engine_type) {
                $q->where('engine_type_id', 'LIKE', $engine_type . '%');
            })->when($req->subject, function(Builder $q, $subject) {
                $q->where('subject', 'LIKE', $subject . '%');
            })->when($req->description, function(Builder $q, $description) {
                $q->where('description', 'LIKE', $description . '%');
            })->when($req->tests_done, function(Builder $q, $tests_done) {
                $q->where('tests_done', 'LIKE', $tests_done . '%');
            })->when($req->knowledge_base, function(Builder $q, $knowledge_base) {
                $q->where('knowledge_base', $knowledge_base);
            })->when($req->order_by, function(Builder $q, $order_by) {
                $q->orderBy($order_by->field, $order_by->value);
            },function ($q) {
                $q->orderBy('created_at', 'DESC');
            })
            // SI ESTE TICKET ESTADO BUSCA POR EL ID
            ->when($req->status, function(Builder $q, $status_id) {
                $q->whereHas('status', function($q2) use ($status_id){
                    $q2->where('ticket_statuses.id', $status_id);
                });
            })
            // SI ESTE TICKET TIENE USUARIOS BUSCA POR EL NOMBRE
            ->when($req->user, function(Builder $q, $user_name){
                $q->whereHas('user', function($q2) use ($user_name){
                    $q2->where('users.name', 'LIKE', $user_name . '%');
                });
            })
            // SI ESTE TICKET TIENE UN CLIENTE BUSCA POR EL ID
            ->when($req->customer, function(Builder $q, $customer_id){
                $q->whereHas('customer', function($q2) use ($customer_id){
                    $q2->where('customers.id', 'LIKE', $customer_id . '%');
                });
            })
            // SI ESTE TICKET TIENE UN DEPARTAMENTO BUSCA POR EL ID
            ->when($req->department, function(Builder $q, $department_id){
                $q->whereHas('department', function($q2) use ($department_id){
                    $q2->where('departments.id', $department_id);
                });
            })
            ->with(['user', 'customer', 'department', 'status'])
            ->withCount('comments')
            ->paginate();

        // $customers = Customer::where('is_active', 1)->get();
        $users = User::where('is_active', 1)->get();

        return response()->json([
            'tickets' => $tickets,
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $this->authorize('tickets.create');

        // $customers = Customer::where('is_active', 1)->get();
        $users = User::where('is_active', 1)->get();

        return view('tickets.create')->with([
            'engine_types' => EngineType::all(),
            // 'customers' => $customers,
            'users' => $users,
        ]);
        
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
            'required' => __(''),
            'exists' => __(':attribute debe existir en la tabla :table'),
            'string' => __(':attribute debe ser de una cadena de texto'),
            'max' => __(':attribute debe ser inferior a :max caracteres'),
            'boolean' => __(':attribute debe ser de tipo boleano (true/false).'),
        ];
        
        $validator = Validator::make($req->all(), [
            'user_id' => ['required', 'numeric', 'exists:users,id' ],
            'department_id' => ['required', 'numeric', 'exists:department,id' ],
            'assigned_to' => ['nullable', 'numeric', 'exists:users,id'],
            'frame_id' => ['required_unless:plate', 'string', 'max:100'],
            'plate' => ['required_unless:frame_id', 'string', 'max:100'],
            'brand' => ['nullable', 'string', 'max:100'],
            'model' => ['nullable', 'string', 'max:100'],
            'engine_type' => ['nullable', 'string'],
            'subject' => ['required', 'string'],
            'description' => ['required', 'string'],
            'test_done' => ['required', 'string'],
            'ask_for' => ['required', 'string', 'max:50'],
            'knwoledge_base' => ['required', 'boolean'],
            'status' => ['nullable', 'string', 'max:100'],
        ], $messages);

        if($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ]);
        }

        $create_ticket = Ticket::create([
            'frame_id' => $req->frame_id,
            'plate' => $req->plate,
            'brand' => $req->brand,
            'model' => $req->model,
            'engine_type' => $req->engine_type,
            'subject' => $req->subject,
            'description' => $req->description,
            'test_done' => $req->test_done,
            'ask_for' => $req->ask_for,
            'knwoledge_base' => $req->knwoledge_base,
            'status' => $req->status ?? 'Abierto',
        ]);

        $get_user = User::find($req->user_id);
        $get_customer = Customer::find($get_user->customer_id);
        $get_department = Department::find($req->department_id);
        $get_assigned_to = $req->assigned_to ? User::find($req->assigned_to) : null;

        $get_user->tickets()->save($create_ticket);
        $get_customer->tickets()->save($create_ticket);
        $get_department->tickets()->save($create_ticket);
        
        if($req->assigned_to) {
            $get_assigned_to->tickets()->save($create_ticket);
        }

        return $create_ticket
            ? response()->json(['success' => __('Ticket creado correctamente.')])
            : response()->json([
                    'error' => __('El Ticket no se ha podido crear, prueba de nuevo mas tarde o póngase en contacto con el administrador.')
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        return view('tickets.view', compact($ticket->with('comments')));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function edit(Ticket $ticket)
    {
        // $this->authorize('tickets.update');

        return view('tickets.edit', compact($ticket));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, Ticket $ticket)
    {
        // $this->authorize('tickets.update');

        $messages = [
            'required' => __(''),
            'exists' => __(':attribute debe existir en la tabla :table'),
            'string' => __(':attribute debe ser de una cadena de texto'),
            'max' => __(':attribute debe ser inferior a :max caracteres'),
            'boolean' => __(':attribute debe ser de tipo boleano (true/false).'),
        ];
        
        $validator = Validator::make($req->all(), [
            'user_id' => ['required', 'numeric', 'exists:users,id' ],
            'department_id' => ['required', 'numeric', 'exists:department,id' ],
            'assigned_to' => ['nullable', 'numeric', 'exists:users,id'],
            'frame_id' => ['required_unless:plate', 'string', 'max:100'],
            'plate' => ['required_unless:frame_id', 'string', 'max:100'],
            'brand' => ['nullable', 'string', 'max:100'],
            'model' => ['nullable', 'string', 'max:100'],
            'engine_type' => ['nullable', 'string'],
            'subject' => ['required', 'string'],
            'description' => ['required', 'string'],
            'test_done' => ['required', 'string'],
            'ask_for' => ['required', 'string', 'max:50'],
            'knwoledge_base' => ['required', 'boolean'],
            'status' => ['nullable', 'string', 'max:100'],
        ], $messages);

        if($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ]);
        }

        $updated = $ticket->update([
            'frame_id' => $req->frame_id,
            'plate' => $req->plate,
            'brand' => $req->brand,
            'model' => $req->model,
            'engine_type' => $req->engine_type,
            'subject' => $req->subject,
            'description' => $req->description,
            'test_done' => $req->test_done,
            'ask_for' => $req->ask_for,
            'knwoledge_base' => $req->knwoledge_base,
            'status' => $req->status ?? 'Abierto',
        ]);

        $get_user = User::find($req->user_id);
        $get_customer = Customer::find($get_user->customer_id);
        $get_department = Department::find($req->department_id);
        $get_assigned_to = $req->assigned_to ? User::find($req->assigned_to) : null;

        $get_user->tickets()->save($ticket);
        $get_customer->tickets()->save($ticket);
        $get_department->tickets()->save($ticket);
        
        if($req->assigned_to) {
            $get_assigned_to->tickets()->save($ticket);
        }

        return $updated
            ? response()->json(['success' => __('Ticket actualizado correctamente.')])
            : response()->json([
                'error' => __('El Ticket no se ha podido actualizar, prueba de nuevo mas tarde o póngase en contacto con el administrador.')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        $this->authorize('tickets.destroy');

        $ticket->update([
            'deleted_by' => auth()->user()->id
        ]);

        $deleted = $ticket->delete();

        return $deleted
            ? response()->json(['success' => __('Ticket eliminado correctamente.')])
            : response()->json([
                'error' => __('El Ticket no se ha podido eliminar, prueba de nuevo mas tarde o póngase en contacto con el administrador.')
        ]);
    }

    public function get_ticket_counter(TicketStatus $ticketStatus = null) {

        return $ticketStatus
            ? $ticketStatus->tickets()->count()
            : Ticket::count();
    }
}
