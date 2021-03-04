<?php

namespace App\Http\Controllers;

use App\Exports\TicketExport;
use App\Jobs\GetCalls;
use App\Models\Attachment;
use App\Models\Brand;
use App\Models\Call;
use App\Models\CarModel;
use App\Models\Customer;
use App\Models\Department;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\User;
use App\Scopes\RoleTicketFilterScope;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Str;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
            $tickets = Ticket::filterTickets()->skip($req->offset ?? 0)->take(10)->get();

            return response()->json([
                'success' => true,
                'tickets' => $tickets,
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
        $custom_attributes = [
            'user_id' => __('Usuario'),
            'department_id' => __('Departamento'),
            'assigned_to' => __('Asignado a'),
            'frame_id' => __('Número de Bastidor'),
            'plate' => __('Matrícula'),
            'brand_id' => __('Marca'),
            'model_id' => __('Modelo'),
            'engine_type' => __('Tipo de Motor'),
            'subject' => __('Asunto'),
            'description' => __('Descripción'),
            'test_done' => __('Pruebas realizadas'),
            'ask_for' => __('Solicito'),
            'status' => __('Estado'),
            'files' => __('Ficheros')
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
            'customer_id' => ['required', 'numeric', 'exists:customers,id'],
            'user_id' => ['required', 'numeric', 'exists:users,id'],
            'department_id' => ['required', 'numeric', 'exists:departments,id'],
            'assigned_to' => ['nullable', 'numeric', 'exists:users,id'],
            'frame_id' => ['nullable', 'string', 'max:100'],
            'plate' => ['nullable', 'string', 'max:100'],
            'brand_id' => ['nullable', 'numeric', 'exists:brands,id'],
            'model_id' => ['nullable', 'numeric', 'exists:car_models,id'],
            'other_brand_model' => ['nullable', 'string', 'max:255'],
            'engine_type' => ['nullable', 'string'],
            'subject' => ['required', 'string'],
            'description' => ['required', 'string'],
            'tests_done' => ['nullable', 'string'],
            'ask_for' => ['required', 'string', 'max:50'],
            'status' => ['nullable', 'string', 'max:100', 'exists:ticket_statuses,id'],
            'calls' => ['nullable', 'array'],
            'files' => ['array', 'nullable', 'max:25600']
        ], $messages, $custom_attributes);
        // return $validator->errors();

        if ($validator->fails()) {
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
        $get_brand = $req->brand_id ? Brand::find($req->brand_id) : null;
        $get_model = $req->model_id ? CarModel::find($req->model_id) : null;

        $lastID = Ticket::withoutGlobalScope(RoleTicketFilterScope::class)->latest()->first()->id;
        // CREATING TICKET
        try {
            //code...
            $create_ticket = Ticket::create([
                'custom_id' => Str::upper($get_department->code) . now()->year . '-' . str_pad(($lastID + 1), 5, '0', STR_PAD_LEFT),
                'frame_id' => $req->frame_id,
                'other_brand_model' => $req->other_brand_model,
                'plate' => $req->plate,
                'engine_type' => $req->engine_type,
                'subject' => $req->subject,
                'description' => $req->description,
                'tests_done' => $req->tests_done,
                'ask_for' => $req->ask_for,
                'knowledge_base' => 0,
                'created_by' => auth()->user()->id,
                'ticket_status_id' => 1
            ]);
        } catch (\Throwable $th) {
            //throw $th;
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
        // ASSIGN USER
        $create_ticket->user()->associate($get_user);
        // ASSIGN CUSTOMER
        $create_ticket->customer()->associate($get_customer);
        // ASSIGN DEPARTMENT
        $create_ticket->department()->associate($get_department);

        if ($req->assigned_to) {
            // ASSIGN USER ASSIGNED TO THIS TICKET
            $create_ticket->tickets()->associate($get_assigned_to);
        }
        if ($req->calls) {
            foreach ($get_calls as $call) {
                // ASSIGN CALLS TO THIS TICKET
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
            ? response()->json(['success' => __('Ticket creado correctamente.')], 200)
            : response()->json([
                'error' => __('El Ticket no se ha podido crear, prueba de nuevo mas tarde o póngase en contacto con el administrador.')
            ], 422);
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

        return response()->json([
            'success' => true,
            'ticket' => $ticket->load(['customer', 'user', 'department', 'brand', 'car_model'])
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
        $custom_attributes = [
            'user_id' => __('Usuario'),
            'department_id' => __('Departamento'),
            'assigned_to' => __('Asignado a'),
            'frame_id' => __('Número de Bastidor'),
            'plate' => __('Matrícula'),
            'brand_id' => __('Marca'),
            'model_id' => __('Modelo'),
            'engine_type' => __('Tipo de Motor'),
            'subject' => __('Asunto'),
            'description' => __('Descripción'),
            'test_done' => __('Pruebas realizadas'),
            'ask_for' => __('Solicito'),
            'status' => __('Estado'),
            'knowledge_base' => __('FAQ\'s')
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
            'brand_id' => ['nullable', 'numeric', 'exists:brands,id'],
            'model_id' => ['nullable', 'numeric', 'exists:car_models,id'],
            'other_brand_model' => ['nullable', 'string', 'max:255', 'exists:car_models,id'],
            'engine_type' => ['nullable', 'string'],
            'subject' => ['required', 'string'],
            'description' => ['required', 'string'],
            'tests_done' => ['required', 'string'],
            'ask_for' => ['required', 'string', 'max:50'],
            'calls' => ['nullable', 'array'],
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
            'tests_done' => $req->tests_done ?? $ticket->tests_done,
            'ask_for' => $req->ask_for ?? $ticket->ask_for,
            'knowledge_base' => $req->knowledge_base ? 1 : 0,
            'ticket_status_id' => $req->status  ?? $ticket->ticket_status_id,
        ]);

        $get_user = User::find($req->user_id);
        $get_customer = Customer::find($get_user->customer_id);
        $get_department = Department::find($req->department_id);
        $get_assigned_to = $req->assigned_to ? User::find($req->assigned_to) : null;
        $get_calls = $req->calls ? Call::find($req->calls) : null;
        $get_brand = $req->brand_id ? Brand::find($req->brand_id) : null;
        $get_car_model = $req->model_id ? CarModel::find($req->model_id) : null;

        // ? ASSIGNING DATA
        // ASSIGN USER
        $ticket->user()->associate($get_user);
        // ASSIGN CUSTOMER
        $ticket->customer()->associate($get_customer);
        // ASSIGN DEPARTMENT
        $ticket->department()->associate($get_department);

        if ($req->assigned_to) {
            // ASSIGN USER ASSIGNED TO THIS TICKET
            $ticket->tickets()->associate($get_assigned_to);
        }
        if ($req->calls) {
            foreach ($get_calls as $call) {
                // ASSIGN CALLS TO THIS TICKET
                $ticket->calls()->save($call);
            }
        }
        if ($req->brand_id) {
            $ticket->brand()->associate($get_brand);
        }
        if ($req->model_id) {
            $ticket->car_model()->associate($get_car_model);
        }

        // ? SAVE ASSOCIATED DATA
        $ticket->save();

        Call::where('ticket_id', $ticket->id)->each(function ($call) {
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
            ? response()->json(['success' => true, 'msg' => __('Ticket eliminado correctamente.')])
            : response()->json([
                'error' => true,
                'msg' => __('El Ticket no se ha podido eliminar, prueba de nuevo mas tarde o póngase en contacto con el administrador.')
            ]);
    }

    public function get_ticket_counters(Request $req)
    {
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
                $pdf = PDF::loadView('exports.pdfs.tickets', ['tickets' => $tickets])
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
}
