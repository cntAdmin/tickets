<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    
    {{-- FONTA AWESOME ICONS --}}
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <title>Reporte de Tickets</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Asunto</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Llamadas</th>
                <th>Adjuntos</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tickets as $ticket)
                <tr>
                    <th>{{ $ticket->custom_id }}</th>
                    <td>{{ $ticket->customer->comercial_name ?? $ticket->customer->fiscal_name  }}</td>
                    <td>{{ $ticket->subject}}...</td><i class="fa fa-call"></i>
                    <td>{{ \Carbon\Carbon::parse($ticket->created_at)->format('d-m-Y H:i:s') }}</td>
                    <td>{{ $ticket->status->name }}</td>
                    <td>{{ $ticket->calls_count }}</td>
                    <td>{{ count($ticket->comment_attachments) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>