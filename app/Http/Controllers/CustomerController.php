<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $customers = Customer::when($req->cif, function(Builder $q, $cif){
            $q->where('cif', 'LIKE', $cif . '%');
        })->when($req->fiscal_name, function(Builder $q, $fiscal_name){
            $q->where('fiscal_name', 'LIKE', $fiscal_name . '%');
        })->when($req->comercial_name, function(Builder $q, $comercial_name){
            $q->where('comercial_name', 'LIKE', $comercial_name . '%');
        })->when($req->phone, function(Builder $q, $phone){
            $q->where('phone', 'LIKE', $phone . '%');
        })->when($req->email, function(Builder $q, $email){
            $q->where('email', 'LIKE', $email . '%');
        })->when($req->street, function(Builder $q, $street){
            $q->where('street', 'LIKE', $street . '%');
        })->when($req->town, function(Builder $q, $town){
            $q->where('town', 'LIKE', $town . '%');
        })->when($req->city, function(Builder $q, $city){
            $q->where('city', 'LIKE', $city . '%');
        })->when($req->country, function(Builder $q, $country){
            $q->where('country', 'LIKE', $country . '%');
        })->when($req->postcode, function(Builder $q, $postcode){
            $q->where('postcode', 'LIKE', $postcode . '%');
        })->when($req->shop, function(Builder $q, $shop){
            $q->where('shop', 'LIKE', $shop . '%');
        })->when($req->is_active, function(Builder $q, $is_active){
            $q->where('is_active', $is_active);
        })
        // COUNT OF USERS PER CUSTOMER
        ->withCount('users')
        ->orderBy('id', 'ASC')
        ->paginate();

        return response()->json([
            'customers' => $customers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        $this->authorize('customers.create');

        $messages = [
            'required' => __(':attibute es obligatorio.'),
            'string' => __(':attibute debe ser una cadena de texto.'),
            'max' => __(':attribute debe ser inferior a :max caracteres.'),
            'email' => __(':attribute debe ser de tipo email.'),
            'boolean' => __(':attribute debe ser tipo boleano (true/false)'),
        ];

        $validator = Validator::make($req->all(), [
            'cif' => ['required', 'string', 'max:9', 'unique:customers,cif'],
            'fiscal_name' => ['required', 'string', 'max:255'],
            'comercial_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'min:6','max:15'],
            'email' => ['required', 'email'],
            'street' => ['nullable', 'string', 'max:255'],
            'town' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'postcode' => ['nullable', 'string', 'max:255'],
            'shop' => ['required','string', 'max:255'],
            'is_active' => ['required', 'boolean'],
        ], $messages);

        if($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ]);
        }

        $create_customer = Customer::create([
            'cif' => $req->cif,
            'fiscal_name' => $req->fiscal_name,
            'comercial_name' => $req->comercial_name,
            'phone' => $req->phone,
            'email' => $req->email,
            'street' => $req->street,
            'town' => $req->town,
            'city' => $req->city,
            'country' => $req->country,
            'postcode' => $req->postcode,
            'shop' => $req->shop,
            'is_active' => $req->is_active ? 1 : 0,
        ]);

        $create_user = $create_customer->users()->create([
            'name' => $req->comercial_name,
            'phone' => $req->phone,
            'email' => $req->email,
            'email_verified_at' => now(),
            'password' => Hash::make(str_random(1)),
            'is_active' => true
        ]);

        $create_user->assignRole('customer');

        /**
         * 
         * ¡¡¡PROBAR EL USO DE SEND RESET LINK A VER SI FUNCIONA!!!
         * 
         */
        $create_user->sendResetLinkEmail();

        return $create_user &&  $create_customer
            ? response()->json(['success' => __('Cliente cread correctamente, también se le ha creado un usuario y se le ha mandado un email para restablecer la contraseña.')])
            : response()->json(['error' => __('Lo sentimos, algo ha ido mal, vuelva a intentarlo mas tarde.')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        $this->authorize('customers.update');

        return view('customers.edit', compact($customer));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, Customer $customer)
    {
        $this->authorize('customers.update');

        $messages = [
            'required' => __(':attibute es obligatorio.'),
            'string' => __(':attibute debe ser una cadena de texto.'),
            'max' => __(':attribute debe ser inferior a :max caracteres.'),
            'email' => __(':attribute debe ser de tipo email.'),
            'boolean' => __(':attribute debe ser tipo boleano (true/false)'),
        ];

        $validator = Validator::make($req->all(), [
            'cif' => ['required', 'string', 'max:9', 'unique:customers,cif'],
            'fiscal_name' => ['required', 'string', 'max:255'],
            'comercial_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'min:6','max:15'],
            'email' => ['required', 'email'],
            'street' => ['nullable', 'string', 'max:255'],
            'town' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'postcode' => ['nullable', 'string', 'max:255'],
            'shop' => ['required','string', 'max:255'],
            'is_active' => ['required', 'boolean'],
        ], $messages);

        if($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ]);
        }
        $updated = $customer->update([
            'cif' => $req->cif,
            'fiscal_name' => $req->fiscal_name,
            'comercial_name' => $req->comercial_name,
            'phone' => $req->phone,
            'email' => $req->email,
            'street' => $req->street,
            'town' => $req->town,
            'city' => $req->city,
            'country' => $req->country,
            'postcode' => $req->postcode,
            'shop' => $req->shop,
            'is_active' => $req->is_active ? 1 : 0,
        ]);

        return $updated
            ? response()->json(['success' => __('Cliente actualizado correctamente.')])
            : response()->json(['error' => __('Lo sentimos, algo ha ido mal, vuelva a intentarlo mas tarde.')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $this->authorize('customers.destroy');
        
        $customer->update([
            'deleted_by' => auth()->user()->id
        ]);

        $deleted = $customer->delete();

        return $deleted 
            ? response()->json(['success' => __('Cliente eliminado correctamente.')])
            : response()->json(['error' => __('Lo sentimos, algo ha ido mal, vuelva a intentarlo mas tarde.')]);
    }
}
