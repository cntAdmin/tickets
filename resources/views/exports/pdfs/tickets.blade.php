<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        .table{width:100%;margin-bottom:1rem;color:#212529}
        .table-hover tbody tr:hover{color:#212529;background-color:rgba(0,0,0,.075)}
        .table-striped tbody tr:nth-of-type(odd){background-color:rgba(0,0,0,.05)}
        .table-dark.table-striped tbody tr:nth-of-type(odd){background-color:rgba(255,255,255,.05)}
        .table .thead-dark th{color:#fff;background-color:#343a40;border-color:#454d55}
        .text-uppercase{text-transform:uppercase!important}
        .text-center{text-align:center!important}
        
        </style>
    <title>Reporte de Tickets</title>
</head>
    <table class="table table-hover table-striped shadow text-left">
        <thead class="thead-dark">
            <tr>
                <th class="text-center" scope="col">#</th>
                <th scope="col">Cliente</th>
                <th scope="col">Asunto</th>
                <th class="text-center" scope="col">Fecha</th>
                <th class="text-center" scope="col">Estado</th>
                <th class="text-center" scope="col">Llamadas</th>
                <th class="text-center" scope="col">Adjuntos</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tickets as $ticket)
                <tr>
                    <th class="text-center text-uppercase" scope="row">{{ $ticket->custom_id }}</th>
                    <td>{{ $ticket->customer->comercial_name ?? $ticket->customer->fiscal_name  }}</td>
                    <td>{{ $ticket->subject_short}}...</td><i class="fa fa-call"></i>
                    <td class="text-center" >{{ \Carbon\Carbon::parse($ticket->created_at)->format('d-m-Y H:i:s') }}</td>
                    <td class="text-center">{{ $ticket->status->name }}</td>
                    <td class="text-center">{{ $ticket->calls_count }}</td>
                    <td class="text-center">{{ count($ticket->comment_attachments) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </body>
</html>