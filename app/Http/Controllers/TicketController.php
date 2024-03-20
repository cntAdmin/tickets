<?php

namespace App\Http\Controllers;

use App\Models\Call;
use App\Models\User;
use App\Models\Brand;
use App\Jobs\GetCalls;
use App\Models\Ticket;
use App\Models\CarModel;
use App\Models\Customer;
use App\Models\Attachment;
use App\Models\Department;
use App\Models\TicketStatus;
use App\Exports\TicketExport;
use App\Imports\TicketsImport;
// use App\Models\Comment;
// use App\Scopes\RoleTicketFilterScope;

use Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    public function index(Request $req)
    {
        if ($req->ajax()) {
            $tickets = Ticket::filterTickets()->paginate();
            return response()->json([
                'success' => true,
                'tickets' => $tickets,
            ]);
        }
    }

    public function mobile_index(Request $req)
    {
        if ($req->ajax()) {
            $tickets = Ticket::filterTickets()->skip($req->offset)->take(10)->get();

            return response()->json([
                'success' => true,
                'tickets' => $tickets,
                'req_offset' => $req->offset,
            ]);
        }
    }

    public function store(Request $req)
    {
        $custom_attributes = [
            'customer_id' => __('Cliente'),
            'user_id' => __('Contacto'),
            'department_id' => __('Departamento'),
            'assigned_to_id' => __('Asignado a'),
            'frame_id' => __('Número de Bastidor'),
            'plate' => __('Matrícula'),
            'brand_id' => __('Marca'),
            'model_id' => __('Modelo'),
            'engine_type' => __('Tipo de Motor'),
            'subject' => __('Asunto'),
            'description' => __('Descripción'),
            // 'test_done' => __('Pruebas realizadas'),
            'ask_for_id' => __('Solicito'),
            'status' => __('Estado'),
            'files' => __('Ficheros')
        ];

        $messages = [
            'required' => __("':attribute' es un campo requerido."),
            'exists' => __("':attribute' debe existir en la tabla :table."),
            'string' => __("':attribute' debe ser de una cadena de texto."),
            'max' => __("':attribute' debe ser inferior a :max caracteres."),
            'boolean' => __("':attribute' debe ser de tipo boleano (true/false)."),
        ];

        $validator = Validator::make($req->all(), [
            'customer_id' => ['required', 'numeric', 'exists:customers,id'],
            'user_id' => ['required', 'numeric', 'exists:users,id'],
            'department_id' => ['required', 'numeric', 'exists:departments,id'],
            'assigned_to_id' => ['required', 'numeric', 'exists:users,id'],
            'frame_id' => ['nullable', 'string', 'max:100'],
            'plate' => ['nullable', 'string', 'max:100'],
            'brand_id' => ['nullable', 'sometimes', 'exists:brands,id'],
            'model_id' => ['nullable', 'sometimes', 'exists:car_models,id'],
            'other_brand_model' => ['nullable', 'string', 'max:255'],
            'engine_type' => ['nullable', 'string'],
            'subject' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            // 'tests_done' => ['nullable', 'string'],
            'ask_for_id' => ['required', 'numeric', 'exists:ask_fors,id'],
            'status' => ['nullable', 'string', 'max:100', 'exists:ticket_statuses,id'],
            'calls' => ['nullable', 'array'],
            'files' => ['array', 'nullable', 'max:25600']
        ], $messages, $custom_attributes);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 200);
        }
        // GET DATA TO ASSIGN
        $get_user = User::find($req->user_id);
        $get_customer = Customer::find($get_user->customer_id);
        $get_department = Department::find($req->department_id);
        $get_status = TicketStatus::find($req->status ?? 1);
        $get_calls = $req->calls ? Call::find($req->calls) : null;
        $get_brand = $req->brand_id ? Brand::find($req->brand_id) : null;
        $get_model = $req->model_id ? CarModel::find($req->model_id) : null;

        $lastID = Ticket::getLastID();

        try {
            $create_ticket = Ticket::create([
                'customer_id' => $req->customer_id,
                'user_id' => $req->user_id,
                'department_id' => $req->department_id,
                'assigned_to_id' => $req->assigned_to_id,
                'custom_id' => Str::upper($get_department->code) . now()->year . '-' . str_pad(($lastID), 5, '0', STR_PAD_LEFT),
                'frame_id' => $req->frame_id,
                'other_brand_model' => $req->other_brand_model,
                'plate' => $req->plate,
                'engine_type' => $req->engine_type,
                'subject' => $req->subject,
                'description' => $req->description,
                // 'tests_done' => $req->tests_done !== 'null' ? $req->tests_done : null,
                'ask_for_id' => $req->ask_for_id,
                'knowledge_base' => 0,
                'created_by' => auth()->user()->id,
                'ticket_status_id' => 1,
                'answered' => auth()->user()->roles[0]->id <= 4 ? true : false
            ]);    
        } catch (\Throwable $th) {
            return $th;
        }

        if ($req->file('files')) {
            foreach ($req->file('files') as $file) {
                $stored_file = Storage::disk('public')->put('media/' . now()->year . '/' . str_pad(now()->month, 2, '0', STR_PAD_LEFT), $file);
                $attachment = Attachment::create([
                    'name' => $file->getClientOriginalName(),
                    'path' => $stored_file
                ]);
                $create_ticket->attachments()->save($attachment);
            }
        }

        // ? ASSIGNING DATA
        // ASSOCIATE STATUS
        $create_ticket->status()->associate($get_status);

        if ($req->calls) {
            foreach ($get_calls as $call) {
                $create_ticket->calls()->save($call);
            }
        }
        if ($req->brand_id) {
            $create_ticket->brand()->associate($get_brand);
        }
        if ($req->model_id) {
            $create_ticket->car_model()->associate($get_model);
        }

        // ? SAVE ASSOCIATED DATA
        $create_ticket->save();

        return $create_ticket
            ? response()->json(['success' => true, 'msg' =>  __('Ticket creado correctamente.')], 200)
            : response()->json([
                'error' => true,
                'msg' => __('El Ticket no se ha podido crear, prueba de nuevo mas tarde o póngase en contacto con el administrador.')
            ], 422);
    }

    public function show(Ticket $ticket)
    {
        if(auth()->check()) {
            $inserted = GetCalls::dispatch();
        }

        return response()->json([
            'success' => true,
            'ticket' => $ticket->load(['customer', 'user', 'department', 'brand', 'car_model','ask_for'])
        ]);
    }

    public function edit(Ticket $ticket)
    {
        // GET NEW CALLS
        $inserted = GetCalls::dispatch($ticket);

        return view('tickets.edit')->with([
            'ticket' => $ticket,
            'inserted' => $inserted
        ]);
    }

    public function update(Request $req, Ticket $ticket)
    {
        $custom_attributes = [
            'customer_id' => __('Cliente'),
            'user_id' => __('Contacto'),
            'department_id' => __('Departamento'),
            'assigned_to_id' => __('Asignado a'),
            'frame_id' => __('Número de Bastidor'),
            'plate' => __('Matrícula'),
            'brand_id' => __('Marca'),
            'model_id' => __('Modelo'),
            'engine_type' => __('Tipo de Motor'),
            'subject' => __('Asunto'),
            'description' => __('Descripción'),
            // 'test_done' => __('Pruebas realizadas'),
            'ask_for_id' => __('Solicito'),
            'status' => __('Estado'),
            'knowledge_base' => __('FAQ\'s')
        ];

        $messages = [
            'required' => __("':attribute' es un campo requerido."),
            'exists' => __("':attribute' debe existir en la tabla :table.'"),
            'string' => __("':attribute' debe ser de una cadena de texto."),
            'max' => __("':attribute' debe ser inferior a :max caracteres."),
            'boolean' => __("':attribute' debe ser de tipo boleano (true/false)."),
        ];

        $validator = Validator::make($req->all(), [
            'customer_id' => ['required', 'numeric', 'exists:customers,id'],
            'user_id' => ['required', 'numeric', 'exists:users,id'],
            'department_id' => ['required', 'numeric', 'exists:departments,id'],
            'assigned_to_id' => ['required', 'numeric', 'exists:users,id'],
            'frame_id' => ['nullable', 'sometimes', 'string', 'max:100'],
            'plate' => ['nullable', 'string', 'max:100'],
            'brand_id' => ['nullable', 'sometimes', 'exists:brands,id'],
            'model_id' => ['nullable', 'sometimes', 'exists:car_models,id'],
            'other_brand_model' => ['nullable', 'string', 'max:255'],
            'engine_type' => ['nullable', 'string'],
            'subject' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            // 'tests_done' => ['nullable', 'string'],
            'ask_for_id' => ['required', 'numeric', 'exists:ask_fors,id'],
            'status' => ['nullable', 'string', 'max:100', 'exists:ticket_statuses,id'],
            'calls' => ['nullable', 'array'],
            'files' => ['array', 'nullable', 'max:25600'],
            'knowledge_base' => ['nullable', 'boolean']
        ], $messages, $custom_attributes);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ]);
        }

        $updated = $ticket->update([
            'frame_id' => $req->frame_id ?? $ticket->frame_id,
            'plate' => $req->plate ?? $ticket->plate,
            'other_brand_model' => $req->other_brand_model,
            'engine_type' => $req->engine_type ?? $ticket->engine_type,
            'subject' => $req->subject ?? $ticket->subject,
            'description' => $req->description ?? $ticket->description,
            // 'tests_done' => $req->tests_done ?? $ticket->tests_done,
            'ask_for_id' => $req->ask_for_id ?? $ticket->ask_for_id,
            'knowledge_base' => $req->knowledge_base ? 1 : 0,
            'ticket_status_id' => $req->status  ?? $ticket->ticket_status_id,
            'assigned_to_id' => $req->assigned_to_id ?? $ticket->assigned_to_id,
        ]);

        if ($req->file('files')) {
            foreach ($req->file('files') as $file) {
                $stored_file = Storage::disk('public')->put('media/' . now()->year . '/' . str_pad(now()->month, 2, '0', STR_PAD_LEFT), $file);
                $attachment = Attachment::create([
                    'name' => $file->getClientOriginalName(),
                    'path' => $stored_file
                ]);
                $ticket->attachments()->save($attachment);
            }
        }

        $get_user = User::find($req->user_id);
        $get_customer = Customer::find($get_user->customer_id);
        $get_department = Department::find($req->department_id);
        $get_brand = $req->brand_id ? Brand::find($req->brand_id) : null;
        $get_car_model = $req->model_id ? CarModel::find($req->model_id) : null;

        // ? ASSIGNING DATA
        // ASSIGN USER
        $ticket->user()->associate($get_user);
        // ASSIGN CUSTOMER
        $ticket->customer()->associate($get_customer);
        // ASSIGN DEPARTMENT
        $ticket->department()->associate($get_department);

        if ($req->brand_id) {
            $ticket->brand()->associate($get_brand);
        } else {
            $ticket->brand()->dissociate();
        }

        if ($req->model_id) {
            $ticket->car_model()->associate($get_car_model);
        } else {
            $ticket->car_model()->dissociate();
        }

        // ? SAVE ASSOCIATED DATA
        $ticket->save();

        // ? ASSOCIATE CALLS TO TICKET
        Call::where('ticket_id', $ticket->id)->each(function ($call) {
            $call->update(['ticket_id' => null]);
        });

        $get_calls = $req->calls ? Call::find(array_values($req->calls)) : [];
        
        foreach ($get_calls as $call) {
            $call->ticket()->associate($ticket->id);
            $call->save();
        }
        
        return $updated
            ? response()->json(['success' => true, 'msg' => __('Ticket actualizado correctamente.')])
            : response()->json([
                'error' => true,
                'msg' => __('El Ticket no se ha podido actualizar, prueba de nuevo mas tarde o póngase en contacto con el administrador.')
            ]);
    }

    public function destroy(Ticket $ticket)
    {
        $this->authorize('tickets.destroy');

        $ticket->update([
            'deleted_by' => auth()->user()->id
        ]);

        $deleted = $ticket->delete();

        return $deleted
            ? response()->json(['success' => true, 'msg' => __('Ticket eliminado correctamente.')])
            : response()->json([
                'error' => true,
                'msg' => __('El Ticket no se ha podido eliminar, prueba de nuevo mas tarde o póngase en contacto con el administrador.')
            ]);
    }

    public function get_ticket_counters(Request $req)
    {
        // Incrementamos límite de memoria ya que hay una gran cantidad de tickets y necesitamos contabilizarlos.
        ini_set('memory_limit', '1024M');

        $new_tickets = Ticket::filterTickets()->where('ticket_status_id', 1)->get()->count();
        $opened = Ticket::filterTickets()->where('ticket_status_id', 2)->get()->count();
        $closed = Ticket::filterTickets()->where('ticket_status_id', 3)->get()->count();
        $resolved = Ticket::filterTickets()->where('ticket_status_id', 4)->get()->count();

        return response()->json([
            'newTickets' => $new_tickets,
            'opened' => $opened,
            'closed' => $closed,
            'resolved' => $resolved,
        ]);
    }

    public function toogle_faqs_ticket(Ticket $ticket)
    {
        $updated = $ticket->update([
            'knowledge_base' => !$ticket->knowledge_base
        ]);
        return $updated
            ? response()->json(['success' => true, 'msg' => ($ticket->knowledge_base ? 'Añadido a FAQs' : 'Quitado de FAQs')])
            : response()->json(['error' => true, 'msg' => __('No se ha podido modificar el estado de este ticket')]);
    }

    public function export_tickets(Request $req)
    {
        $tickets = Ticket::filterTickets()->get();
        if($tickets->count() > 300) {
            return response()->json([
                'error' => true,
                'errors' => [
                    ['tickets' => 'Fichero demasiado grande, por favor haga otra búsqueda mas concreta']
                ]
            ]);
        }
        switch ($req->type) {
            case 'excel':
                if (!Storage::exists('exports/excels')) {
                    Storage::makeDirectory('exports/excels');
                }

                $filename = 'tickets-' . now()->toDateTimeString() . '.xlsx';
                $storage = 'exports/excels/' . $filename;
                $store = Excel::store(new TicketExport($req), $storage);
                break;
            case 'pdf':
                if (!Storage::exists('exports/pdfs')) {
                    Storage::makeDirectory('exports/pdfs');
                }
                $filename = 'tickets-' . now()->format('Y-m-d_H-i-s') . '.pdf';
                $storage = 'exports/pdfs/' . $filename;
                $tickets = Ticket::filterTickets()->get();
                $pdf = PDF::loadView('exports.tickets', ['tickets' => $tickets, 'type' => $req->type])
                    ->setPaper('a4', 'landscape')
                    ->setOptions([
                        'defaultFont' => 'sans-serif',
                        'debugCss' => true
                    ])->save('storage/' . $storage);
                break;
        }
        $headers = [
            'Content-Type' => 'application/*',
        ];

        return response()->download(Storage::path($storage), $filename, $headers);
    }

    public function answered_tickets() 
    {
        $answered = Ticket::where(function(Builder $q) {
            if(auth()->user()->roles[0]->id > 4) {
                $q->where('answered', true);
            } else {
                $q->where('answered', false);
            }
        })->count();

        return response()->json([
            'success' => true,
            'answered' => $answered
        ]);
    }

    public function ticket_import() 
    {
        Excel::import(new TicketsImport, 'imports/Importar_Incidencias_prueba.csv');
    }

    public function view_ticket(Ticket $ticket) 
    {
        return view('tickets.view')->with(['ticket' => $ticket]);
    }

    public function get_answered()
    {
        $answered = Ticket::answered()->count();

        return response()->json([
            'success' => true,
            'answered' => $answered
        ]);
    }

    public function get_nuevos_tickets()
    {
        $nuevos = Ticket::where('ticket_status_id', 1)->count();

        return response()->json([
            'answered' => $nuevos
        ]);
    }

    public function get_admin_charts(Request $req)
    {
        // Promedio tiempo de respuesta de los agentes
        $promedioTiempoRespuestaAgentes = DB::table('tickets')
        ->join('users','users.id', '=', 'tickets.assigned_to_id')
        ->select([
            DB::raw('count(tickets.assigned_to_id) as total_incidencias'), 
            DB::raw('sum(tickets.resolution_time) as resolution_time'), 
            DB::raw('users.name as name')
        ])
        ->where('tickets.ticket_status_id', 4)
        ->whereDate( 'tickets.created_at', '>', now()->subDays($req->days))
        ->groupBy('name')
        ->orderBy('name', 'ASC')
        ->get();

        
        // Incidencias generadas por días pasados por parámetro
        $incidenciasPorDias = DB::table('tickets')->select([
            DB::raw('count(id) as total'), 
            DB::raw('DATE(created_at) as dia')
        ])
        ->whereDate( 'tickets.created_at', '>', now()->subDays($req->days))
        ->groupBy('dia')
        ->get();


        // Incidencias por agentes (Todos los agentes)
        $incidenciasPorAgente = DB::table('tickets')
        ->join('users','users.id', '=', 'tickets.assigned_to_id')
        ->select([
            DB::raw('count(tickets.assigned_to_id) as total'), 
            DB::raw('users.name as name')
        ])
        ->whereDate( 'tickets.created_at', '>', now()->subDays($req->days))
        ->groupBy('name')
        ->orderBy('name', 'ASC')
        ->get();


        // Talleres con más incidencias (5)
        $incidenciasPorTalleres = DB::table('tickets')
        ->join('customers','customers.id', '=', 'tickets.customer_id')
        ->select([
            DB::raw('count(tickets.id) as total'), 
            DB::raw('customers.fiscal_name as name')
        ])
        ->whereDate( 'tickets.created_at', '>', now()->subDays($req->days))
        ->groupBy('name')
        ->orderBy('name', 'ASC')
        ->limit(5)
        ->get();

        
        
        // Obtenemostotal de Incidencias por estado
        $estadoIncidencias = DB::table('tickets')
        ->join('ticket_statuses','ticket_statuses.id', '=', 'tickets.ticket_status_id')
        ->select([
            DB::raw('count(tickets.ticket_status_id) as total'), 
            DB::raw('ticket_statuses.name as estado')
        ])
        ->whereDate( 'tickets.created_at', '>', now()->subDays($req->days))
        ->groupBy('estado')
        ->get();
      

        return response()->json([
            'promedioTiempoRespuestaAgentes' => $promedioTiempoRespuestaAgentes,
            'incidenciasPorDias' => $incidenciasPorDias,
            'incidenciasPorAgente' => $incidenciasPorAgente,
            'incidenciasPorTalleres' => $incidenciasPorTalleres,
            'estadoIncidencias' => $estadoIncidencias,
        ]);
    }

    public function get_all_ask_for()
    {
        return response()->json([
            'success' => true,
            'ask_for' => \App\Models\AskFors::all()->toArray()
        ]);
    }
}