<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
class CustomerController extends Controller
{
    private $messages;

    public function __construct()
    {
        $this->messages = [
            'required' => __(':attribute es obligatorio.'),
            'string' => __(':attribute debe ser una cadena de texto.'),
            'max' => __(':attribute debe ser inferior a :max caracteres.'),
            'email' => __(':attribute debe ser de tipo email.'),
            'boolean' => __(':attribute debe ser tipo boleano (true/false)'),
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        if($req->ajax()) {
            $customers = Customer::when($req->custom_id, function(Builder $q, $custom_id){
                    $q->where('custom_id', 'LIKE', $custom_id . '%');
                })->when($req->cif, function(Builder $q, $cif){
                    $q->where('cif', 'LIKE', $cif . '%');
                })->when($req->fiscal_name, function(Builder $q, $fiscal_name){
                    $q->where('fiscal_name', 'LIKE', '%' . $fiscal_name . '%');
                })->when($req->comercial_name, function(Builder $q, $comercial_name){
                    $q->where('comercial_name', 'LIKE', '%' . $comercial_name . '%');
                })->when($req->phone, function(Builder $q, $phone){
                    $q->where('phone_1', 'LIKE', $phone . '%')
                        ->orWhere('phone_2', 'LIKE', $phone . '%')
                        ->orWhere('phone_3', 'LIKE', $phone . '%');
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
                ->with('users')
                // COUNT OF USERS PER CUSTOMER
                ->withCount('users')
                ->orderBy('custom_id', 'ASC')
                ->paginate();

            return response()->json([
                'customers' => $customers
            ]);
        }
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
        // $this->authorize('customers.create');
        $validator = Validator::make($req->all(), [
            'cif' => ['nullable', 'sometimes', 'string', 'max:9', 'unique:customers,cif'],
            'custom_id' => ['nullable', 'sometimes', 'string', 'max:100', 'unique:customers,custom_id'],
            'fiscal_name' => ['nullable', 'sometimes', 'string', 'max:255'],
            'comercial_name' => ['required', 'string', 'max:255'],
            'phone_1' => ['required', 'sometimes', 'string', 'min:6','max:15'],
            'phone_2' => ['nullable', 'sometimes', 'string', 'min:6','max:15'],
            'phone_3' => ['nullable', 'sometimes', 'string', 'min:6','max:15'],
            'email' => ['required', 'email'],
            'street' => ['nullable', 'sometimes', 'string', 'max:255'],
            'city' => ['nullable', 'sometimes', 'string', 'max:255'],
            'province' => ['nullable', 'sometimes', 'string', 'max:255'],
            'country' => ['nullable', 'sometimes', 'string', 'max:255'],
            'postcode' => ['nullable', 'sometimes', 'string', 'max:255'],
            'shop' => ['required', 'sometimes', 'string', 'max:255'],
            'is_active' => ['nullable', 'sometimes', 'boolean'],
        ], $this->messages);

        if($validator->fails()) {
            return response()->json([
                'error' => true,
                'errors' => $validator->errors()
            ]);
        }
        $create_customer = Customer::create([
            'cif' => $req->cif,
            'custom_id' => $req->custom_id ?? Str::random(10),
            'fiscal_name' => $req->fiscal_name,
            'comercial_name' => $req->comercial_name,
            'phone_1' => $req->phone_1,
            'phone_2' => $req->phone_2,
            'phone_3' => $req->phone_3,
            'email' => $req->email,
            'street' => $req->street,
            'city' => $req->city,
            'province' => $req->province,
            'country' => $req->country,
            'postcode' => $req->postcode,
            'shop' => $req->shop,
            'is_active' => 1,
        ]);

        $create_user = $create_customer->users()->create([
            'name' => $req->comercial_name,
            'username' => 'cliente_'.rand(1,9999999),
            'phone' => $req->phone_1,
            'email' => $req->email,
            'email_verified_at' => now(),
            'password' => Hash::make(Str::random(10)),
            'is_active' => true
        ]);

        $create_user->assignRole('customer');

        if(env('APP_ENV') !== 'local') {
            $create_user->sendResetLinkEmail($req);
        }

        return $create_user &&  $create_customer
            ? response()->json(['success' => true, 'msg' => __('Cliente creado correctamente, también se le ha creado un usuario y se le ha mandado un email para restablecer la contraseña.')])
            : response()->json(['error' =>  true, 'msg' => __('Lo sentimos, algo ha ido mal, vuelva a intentarlo mas tarde.')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        return response()->json([
            'success' => true,
            'customer' => $customer
        ]);
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
        // $this->authorize('customers.update');

        $validator = Validator::make($req->all(), [
            'cif' => ['nullable', 'string', 'max:9', 'unique:customers,cif,' . $customer->id],
            'custom_id' => ['nullable', 'string', 'max:100', 'unique:customers,custom_id,' . $customer->id],
            'fiscal_name' => ['nullable', 'string', 'max:255'],
            'comercial_name' => ['required', 'string', 'max:255'],
            'phone_1' => ['required', 'string', 'min:6','max:15'],
            'phone_2' => ['nullable', 'string', 'min:6','max:15'],
            'phone_3' => ['nullable', 'string', 'min:6','max:15'],
            'email' => ['required', 'email'],
            'street' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'province' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'postcode' => ['nullable', 'string', 'max:255'],
            'shop' => ['required','string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ], $this->messages);

        if($validator->fails()) {
            return response()->json([
                'error' => true,
                'errors' => $validator->errors()
            ]);
        }
        $updated = $customer->update([
            'cif' => $req->cif,
            'fiscal_name' => $req->fiscal_name,
            'comercial_name' => $req->comercial_name,
            'phone_1' => $req->phone_1,
            'phone_2' => $req->phone_2,
            'phone_3' => $req->phone_3,
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
            ? response()->json(['success' => true, 'msg' => __('Cliente actualizado correctamente.')])
            : response()->json(['error' => true, 'msg' => __('Lo sentimos, algo ha ido mal, vuelva a intentarlo mas tarde.')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        // $this->authorize('customers.destroy');

        $customer->update([
            'deleted_by' => auth()->user()->id
        ]);
        
        $deleted = $customer->delete();

        return $deleted 
            ? response()->json(['success' => true, 'msg' => __('Cliente eliminado correctamente.')])
            : response()->json(['error' => true, 'msg' => __('Lo sentimos, algo ha ido mal, vuelva a intentarlo mas tarde.')]);
    }

    public function get_all_customers() {
        $customers = \App\Models\Customer::where('is_active', 1)
                        ->orderBy('comercial_name')
                        ->get()
                        ->toArray();

        return response()->json([
            'success' => true,
            'customers' => $customers
        ]);
    }

    public function get_customers_count() {
        $active = Customer::where('is_active', 1)->get()->count();
        $inactive = Customer::where('is_active', 0)->get()->count();

        return response()->json([
            'active' => $active,
            'inactive' => $inactive
        ]);
    }

    public function get_customer_contacts(Customer $customer) {
        return response()->json([
            'success' => true,
            'contacts' => $customer->contacts()
        ]);
    }
}
