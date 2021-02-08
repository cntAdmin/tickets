<?php

namespace App\Http\Controllers;

use App\Jobs\GetCalls;
use App\Models\Call;
use App\Models\Customer;
use App\Models\Department;
use App\Models\EngineType;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Throwable;

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
                'status' => $req->status ? $req->status : null
            ]);
        }
        $tickets = Ticket::when($req->ticket_id, function(Builder $q, $id) {
                $q->where('custom_id', 'LIKE', $id . '%');
            })->when($req->frame_id, function(Builder $q, $frame_id) {
                $q->where('frame_id', 'LIKE', $frame_id . '%');
            })->when($req->plate, function(Builder $q, $plate) {
                $q->where('plate', 'LIKE', $plate . '%');
            })->when($req->brand, function(Builder $q, $brand) {
                $q->where('brand', 'LIKE', $brand . '%');
            })->when($req->model, function(Builder $q, $model) {
                $q->where('model', 'LIKE', $model . '%');
            })->when($req->engine_type, function(Builder $q, $engine_type) {
                $q->where('engine_type', 'LIKE', $engine_type . '%');
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
            // SI ESTE TICKET ESTADO BUSCA POR EL ID DEL ESTADO
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
        // GET NEW CALLS
        $inserted = GetCalls::dispatch();

        // $customers = Customer::where('is_active', 1)->get();
        $users = User::where('is_active', 1)->get();


        return view('tickets.create')->with([
            // 'customers' => $customers,
            'users' => $users,
            'inserted' => $inserted,
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
        $custom_attributes = [
            'user_id' => __('Usuario'),
            'department_id' => __('Departamento'),
            'assigned_to' => __('Asignado a'),
            'frame_id' => __('Número de Bastidor'),
            'plate' => __('Matrícula'),
            'brand' => __('Marca'),
            'model' => __('Modelo'),
            'engine_type' => __('Tipo de Motor'),
            'subject' => __('Asunto'),
            'description' => __('Descripción'),
            'test_done' => __('Pruebas realizadas'),
            'ask_for' => __('Solicito'),
            'status' => __('Estado'),
        ];

        // return $req->subject;
        $messages = [
            'required' => __(':attribute es un campo requerido.'),
            'exists' => __(':attribute debe existir en la tabla :table.'),
            'string' => __(':attribute debe ser de una cadena de texto.'),
            'max' => __(':attribute debe ser inferior a :max caracteres.'),
            'boolean' => __(':attribute debe ser de tipo boleano (true/false).'),
        ];
        
        $validator = Validator::make($req->all(), [
            'user_id' => ['required', 'numeric', 'exists:users,id'],
            'department_id' => ['required', 'numeric', 'exists:departments,id'],
            'assigned_to' => ['nullable', 'numeric', 'exists:users,id'],
            'frame_id' => ['nullable', 'string', 'max:100'],
            'plate' => ['nullable', 'string', 'max:100'],
            'brand' => ['nullable', 'string', 'max:100'],
            'model' => ['nullable', 'string', 'max:100'],
            'engine_type' => ['nullable', 'string'],
            'subject' => ['required', 'string'],
            'description' => ['required', 'string'],
            'tests_done' => ['required', 'string'],
            'ask_for' => ['required', 'string', 'max:50'],
            'status' => ['nullable', 'string', 'max:100', 'exists:ticket_statuses,id'],
            'calls' => ['nullable', 'array']
        ], $messages, $custom_attributes);
        // return $validator->errors();

        if($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 200);
        }
        // GET DATA TO ASSIGN
        $get_user = User::find($req->user_id);
        $get_customer = Customer::find($get_user->customer_id);
        $get_department = Department::find($req->department_id);
        $get_assigned_to = $req->assigned_to ? User::find($req->assigned_to) : null;
        $get_status = TicketStatus::find($req->status ?? 1);
        $get_calls = $req->calls ? Call::find($req->calls) : null;
        
        // CREATING TICKET
        $create_ticket = Ticket::create([
            'custom_id' => $get_department->code . '-' . now()->timestamp,
            'frame_id' => $req->frame_id,
            'plate' => $req->plate,
            'brand' => $req->brand,
            'model' => $req->model,
            'engine_type' => $req->engine_type,
            'subject' => $req->subject,
            'description' => $req->description,
            'tests_done' => $req->tests_done,
            'ask_for' => $req->ask_for,
            'knowledge_base' => $req->knowledge_base ? 1 : 0,
            'created_by' => auth()->user()->id,
        ]);

            
        // ASSIGNING DATA
            // ASSOCIATE STATUS
            $create_ticket->status()->associate($get_status);
            // ASSIGN USER
            $create_ticket->user()->associate($get_user);
            // ASSIGN CUSTOMER
            $create_ticket->customer()->associate($get_customer);
            // ASSIGN DEPARTMENT
            $create_ticket->department()->associate($get_department);
        
            if($req->assigned_to) {
                // ASSIGN USER ASSIGNED TO THIS TICKET
                $create_ticket->tickets()->associate($get_assigned_to);
            }
            if($req->calls) {
                foreach ($get_calls as $call) {
                    // ASSIGN CALLS TO THIS TICKET
                    $create_ticket->calls()->save($call);
                }
            }
            
            // SAVE ASSOCIATED DATA
            $create_ticket->save();

        return $create_ticket
            ? response()->json(['success' => __('Ticket creado correctamente.')], 200)
            : response()->json([
                    'error' => __('El Ticket no se ha podido crear, prueba de nuevo mas tarde o póngase en contacto con el administrador.')
            ],422);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        $inserted = GetCalls::dispatch();

        return view('tickets.view')->with([
            'ticket' => $ticket->load(['customer', 'user', 'department'])
            ]);
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
        // GET NEW CALLS
        $inserted = GetCalls::dispatch($ticket);

        return view('tickets.edit')->with([
            'ticket' => $ticket,
            'inserted' => $inserted
        ]);
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
            'exists' => __(':attribute debe existir en la tabla :table'),
            'string' => __(':attribute debe ser de una cadena de texto'),
            'max' => __(':attribute debe ser inferior a :max caracteres'),
            'boolean' => __(':attribute debe ser de tipo boleano (true/false).'),
        ];
        
        $validator = Validator::make($req->all(), [
            'user_id' => ['nullable', 'numeric', 'exists:users,id' ],
            'department_id' => ['nullable', 'numeric', 'exists:department,id' ],
            'assigned_to' => ['nullable', 'numeric', 'exists:users,id'],
            'frame_id' => ['nullable', 'string', 'max:100'],
            'plate' => ['nullable', 'string', 'max:100'],
            'brand' => ['nullable', 'string', 'max:100'],
            'model' => ['nullable', 'string', 'max:100'],
            'engine_type' => ['nullable', 'string'],
            'subject' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'test_done' => ['nullable', 'string'],
            'ask_for' => ['nullable', 'string', 'max:50'],
            'knwoledge_base' => ['nullable', 'boolean'],
            'status' => ['nullable', 'string', 'max:100'],
        ], $messages);

        if($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ]);
        }
        $updated = $ticket->update([
            'frame_id' => $req->frame_id ?? $ticket->frame_id,
            'plate' => $req->plate ?? $ticket->plate,
            'brand' => $req->brand ?? $ticket->brand,
            'model' => $req->model ?? $ticket->model,
            'engine_type' => $req->engine_type ?? $ticket->engine_type,
            'subject' => $req->subject ?? $ticket->subject,
            'description' => $req->description ?? $ticket->description,
            'test_done' => $req->test_done ?? $ticket->test_done,
            'ask_for' => $req->ask_for ?? $ticket->ask_for,
            'knwoledge_base' => $req->knwoledge_base ?? $ticket->knwoledge_base,
            'ticket_status_id' => $req->status  ?? $ticket->ticket_status_id,
            ]);
            
        $get_user = $req->user_id ? User::find($req->user_id) : null;
        $get_customer = $get_user ? Customer::find($get_user->customer_id) : null;
        $get_department = $req->department_id ? Department::find($req->department_id) : null;
        $get_assigned_to = $req->assigned_to ? User::find($req->assigned_to) : null;
        
        $get_user ? $get_user->tickets()->save($ticket) : null;
        $get_customer ? $get_customer->tickets()->save($ticket) : null;
        $get_department ? $get_department->tickets()->save($ticket) : null;
        $get_assigned_to ? $get_assigned_to->tickets()->save($ticket) : null;
        
        Call::where('ticket_id', $ticket->id)->each(function($call){
            $call->update(['ticket_id' => null]);
        });
        
        $get_calls = $req->calls ? Call::find(array_values($req->calls)) : [];
    
        foreach ($get_calls as $call) {
            $call->ticket()->associate($ticket->id);
            $call->save();
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

    public function get_ticket_counters() {
        $total_count = Ticket::all()->count();
        $opened = Ticket::where('ticket_status_id', 1)->get()->count();
        $closed = Ticket::where('ticket_status_id', 2)->get()->count();
        $resolved = Ticket::where('ticket_status_id', 3)->get()->count();

        return response()->json([
            'total_count' => $total_count,
            'opened' => $opened,
            'closed' => $closed,
            'resolved' => $resolved,
        ]);
    }
}
