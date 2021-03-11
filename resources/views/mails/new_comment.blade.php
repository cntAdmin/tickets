@component('mail::message')
<b>#{{ $comment->ticket->custom_id }}</b>
<p>
    Hola {{ $comment->ticket->user->name }},
</p>
<p>
    Hola $CLIENTE,

    Nuestros técnicos han respondido tu incidencia, si quieres responder rápidamente puedes pinchar 
    en el enlace de abajo dentro de las próximas 72 horas, 
    en caso de exceder ese tiempo podrás responder desde el panel de incidencias.
</p>

@component('mail::panel')
<p>ID Incidencia: {{ $comment->ticket->custom_id }}</p>
<p>Asunto: {{ $comment->ticket->subject }}</p>
<p>Departamento: {{ $comment->ticket->department->name }}</p>
@endcomponent
<p>
    Puede comprobar el estado de la incidenciao actualizarlo en línea pulsando aquí.
</p>
@component('mail::button', ['url' => config('app.url') . 'ver/' . $comment->_token])
Ver Incidencia
@endcomponent
<p>
    Atentamente,<br />
    {{ config('app.name') }}
</p>
@endcomponent