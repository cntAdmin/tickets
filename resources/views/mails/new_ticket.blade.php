@component('mail::message')
<b>#{{ $ticket->custom_id }}</b>

<p>
    Gracias por contactar con nosotros. Esta es una respuesta automática confirmando la recepción de su incidencia.
    Nuestro equipo se pondrá en contacto con usted en breve. Al responder, asegúrese, por favor,
    de que el ID de la incidencia permanece en el asunto para que podamos seguir sus respuestas.
</p>

@component('mail::panel')
<p>ID Incidencia: {{ $ticket->custom_id }}</p>
<p>Asunto: {{ $ticket->subject }}</p>
<p>Departamento: {{ $ticket->department->name }}</p>
<p>Estado: Nuevo</p>
@endcomponent
<p>
    Puede comprobar el estado de la incidenciao actualizarlo en línea pulsando aquí.
</p>
@component('mail::button', ['url' => config('app.url') . 'incidencia/' . $ticket->id])
Ver Incidencia
@endcomponent
<p>
    Atentamente,<br />
    {{ config('app.name') }}
</p>
@endcomponent