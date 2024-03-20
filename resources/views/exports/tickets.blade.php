<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529
        }

        .table-hover tbody tr:hover {
            color: #212529;
            background-color: rgba(0, 0, 0, .075)
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, .05)
        }

        .table-dark.table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, .05)
        }

        .table .thead-dark th {
            color: #fff;
            background-color: #343a40;
            border-color: #454d55
        }

        .text-uppercase {
            text-transform: uppercase !important
        }

        .text-center {
            text-align: center !important
        }
    </style>

    <title>Reporte de Tickets</title>
</head>
<body>
    <table class="table table-hover table-striped shadow">
        <thead class="thead-dark">
            <tr class="text-center text-uppercase">
                <th>#</th>
                <th>Cliente</th>
                <th>Asunto</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Tiempo Respuesta</th>
                <th>Asignado a...</th>
                <th>Adjuntos</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tickets as $ticket)
                <tr>
                    <th>{{ $ticket->custom_id }}</th>
                    <td>{{ ($ticket->customer && $ticket->customer->comercial_name) ? $ticket->customer->comercial_name : $ticket->customer->fiscal_name ?? ''  }}</td>
                    <td>{{ $ticket->subject}}...</td><i class="fa fa-call"></i>
                    <td>{{ \Carbon\Carbon::parse($ticket->updated_at)->format('d-m-Y H:i:s') }}</td>
                    <td>{{ $ticket->status->name }}</td>
                    @if ($ticket->ticket_status_id == 4)
                        @if($type == 'pdf')
                            <td>
                                {{ (intdiv($ticket->resolution_time , 60) ? intdiv($ticket->resolution_time , 60) .'hrs ' : '') }}
                                {{ (($ticket->resolution_time % 60) > 0) ? ($ticket->resolution_time % 60) .'mins' : '' }}
                            </td>
                        @else
                            <td>{{ $ticket->resolution_time }}</td>
                        @endif
                    @else
                        <td>0</td>
                    @endif
                    @if ($ticket->assigned_to)
                        <td>{{ $ticket->assigned_to->name }}</td>
                    @else
                        <td> - </td>
                    @endif
                    <td>{{ count($ticket->comment_attachments) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>