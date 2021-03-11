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
                <th>Llamadas</th>
                <th>Adjuntos</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tickets as $ticket)
                <tr>
                    <th>{{ $ticket->custom_id }}</th>
                    <td>{{ ($ticket->customer && $ticket->customer->comercial_name) ? $ticket->customer->comercial_name : $ticket->customer->fiscal_name ?? ''  }}</td>
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