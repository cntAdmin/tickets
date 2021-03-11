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

    <title>Reporte de Usuarios</title>
</head>

<body>
    <table class="table table-hover table-striped shadow">
        <thead class="thead-dark">
            <tr class="text-center text-uppercase">
                <th scope="col">INCIDENCIA</th>
                <th class="text-center" scope="col">
                    CLIENTE
                </th>
                <th scope="col">ORIGEN</th>
                <th scope="col">DESTINO</th>
                <th class="text-center" scope="col">FECHA</th>
                <th class="text-center" scope="col">INICIO</th>
                <th class="text-center" scope="col">
                    DURACIÓN <small>(s)</small>
                </th>
                <th scope="col">TIPO</th>
                <th scope="col">ESTADO</th>
            </tr>
        </thead>
        <tbody>
            @foreach($calls as $call)
            <tr>
                <td class="text-left">
                    <button class="btn btn-link btn-sm text-uppercase">{{ $call->ticket->custom_id ?? '' }}</button>
                </td>
                <td class="text-left">
                    {{ ($call->customer && $call->customer->comercial_name) ? $call->customer->comercial_name : '' }}
                </td>
                <td>{{ $call->src }}</td>
                @if($call->is_incoming == 1)
                    <td>
                        {{ $call->dst_extension ? $call->dst_extension : $call->dst }}
                    </td>
                @else
                    <td>{{ $call->dst ? $call->dst : $call->dst_extension }}</td>
                @endif
                <td>{{ \Carbon\Carbon::parse($call->start)->format("d-m-Y") }}</td>
                <td>{{ \Carbon\Carbon::parse($call->start)->format("H:i:s") }}</td>
                <td class="text-center">{{ $call->duration ?? 0 }}</td>
                @if ($call->is_incoming == 1)
                    <td>
                        <i class="text-success fa fa-sign-in-alt" title="Entrante"></i>
                    </td>
                @else
                    <td>
                        <i class="text-warning fa fa-sign-out-alt" title="Saliente"></i>
                    </td>                
                @endif
                <td>{{ $call->disposition }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>