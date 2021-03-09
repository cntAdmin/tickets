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
    
    <title>Reporte de Clientes</title>
</head>

<body>
    <table class="table table-hover table-striped shadow">
        <thead class="thead-dark">
            <tr>
                <th class="text-center" scope="col">#</th>
                <th scope="col">CIF</th>
                <th scope="col">Nombre Fiscal</th>
                <th scope="col">Nombre Comercial</th>
                <th scope="col">Teléfono 1</th>
                <th scope="col">Teléfono 2</th>
                <th scope="col">Teléfono 3</th>
                <th scope="col">Email</th>
                <th scope="col">Tienda</th>
                <th scope="col">Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $cs)
                <tr>
                    <th class="text-center" scope="row">{{ $cs->custom_id }}</th>
                    <td>{{ $cs->cif }}</td>
                    <td>{{ $cs->fiscal_name }}</td>
                    <td>{{ $cs->comercial_name }}</td>
                    <td>{{ $cs->phone_1 }}</td>
                    <td>{{ $cs->phone_2 }}</td>
                    <td>{{ $cs->phone_3 }}</td>
                    <td>{{ $cs->email }}</td>
                    <td>{{ $cs->shop }}</td>
                    <td>
                        @if($cs->active_status)
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