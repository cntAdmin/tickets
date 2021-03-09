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
            <tr>
                <th scope="col">Rol</th>
                <th scope="col">Cliente / Departamento</th>
                <th scope="col">Nombre</th>
                <th scope="col">Usuario</th>
                <th scope="col">Teléfono</th>
                <th scope="col">Email</th>
                <th class="text-center" scope="col">Incidencias</th>
                <th class="text-center" scope="col">Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $u)
            <tr>
                <th scope="row" class="text-capitalize">
                    {{ $u->roles[0]->name }}
                </th>
                @if($u->customer)
                <th>
                    {{ $u->customer->comercial_name }}
                </th>
                @elseif($u->department)
                <th>
                    {{ $u->department->name }}
                </th>
                @else
                <th></th>
                @endif
                <td>{{ $u->fullname }}</td>
                <td>{{ $u->username }}</td>
                <td>{{ $u->phone }}</td>
                <td>{{ $u->email }}</td>
                <td class="text-center">{{ $u->tickets_count }}</td>
                <td>
                    @if($u->active_status)
                    Activo
                    @else
                    Inactivo
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>