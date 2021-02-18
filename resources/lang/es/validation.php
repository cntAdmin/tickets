<?php

return [

    /*
|--------------------------------------------------------------------------
| Validation Language Lines
|--------------------------------------------------------------------------
|
| The following language lines contain the default error messages used by
| the validator class. Some of these rules have multiple versions such
| as the size rules. Feel free to tweak each of these messages here.
|
*/
    'custom' => [
        'department_id.required_without' => 'Este campo es requerido si el cliente no existe.'
    ],
    'attributes' => [
        // TICKETS
        'user_id' => 'Usuario',
        'department_id' => 'Departamento',
        'assigned_to' => 'Asignado a',
        'frame_id' => 'Número de Bastidor',
        'plate' => 'Matrícula',
        'brand_id' => 'Marca',
        'model_id' => 'Modelo',
        'engine_type' => 'Tipo de Motor',
        'subject' => 'Asunto',
        'description' => 'Descripción',
        'test_done' => 'Pruebas realizadas',
        'ask_for' => 'Solicito',
        'status' => 'Estado',
        // USUARIO
        'customer_id' => 'Cliente',
        'department_id' => 'Departamento',
        'name' => 'Nombre',
        'surname' => 'Apellidos',
        'username' => 'Nombre de usuario',
        'email' => 'Email',
        'phone' => 'Teléfono',
        'password' => 'Contraseña',
        'password_confirmation' => 'Rep. Contraseña',
        'is_active' => 'Activo',
        // DEPARTAMENTO
        'name' => 'Nombre',
        'code' => 'Código',
        // POSTS
        'title' => 'Título',
        'description' => 'Descripción',
        'likes' => 'Me gusta',
        'dislikes' => 'No me gusta',
        'featured' => 'Destacado',
        // CUSTOMERS
        'cif' => 'CIF',
        'custom_id' => 'Código Cliente',
        'fiscal_name' => 'Nombre Fiscal',
        'comercial_name' => 'Nombre Comercial',
        'phone' => 'Teléfono',
        'email' => 'Email',
        'street' => 'Dirección',
        'city' => 'Ciudad',
        'province' => 'Provincia',
        'country' => 'País',
        'postcode' => 'Código Postal',
        'shop' => 'Tienda',
        'is_active' => 'Activo'
    ],
    'values' => [
        'customer_id' => [
            'department_id' => 'departamento'
        ],
        'department_id' => [
            'customer_id' => 'cliente'
        ]
    ],


];
