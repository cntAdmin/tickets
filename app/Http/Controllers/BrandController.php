<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    private $messages = [
        'required' => ':attribute es un campo obligatorio.',
        'unique' => ':attribute tiene que ser único, el nombre que esta poniendo ya existe.'
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        if($req->ajax()) {
            $brands = Brand::when($req->name, function(Builder $q, $name) {
                $q->where('name', 'LIKE', '%'. $name . '%');
            })->withCount('models')
            ->paginate();

            return response()->json([
                'success' => true,
                'brands' => $brands
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => ['required', 'unique:brands,name']
        ], $this->messages);

        if($validator->fails()) {
            return response()->json([
                'error' => true,
                'errors' => $validator->errors()
            ]);
        }

        $created = Brand::create([
            'name' => $req->name
        ]);

        return $created
            ? response()->json([ 'success' => true, 'msg' => __('Marca creada correctamente.') ])
            : response()->json([ 'error' => true, 'msg' => __('La marca no se ha podido crear, pruébelo de nuevo más tarde.') ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, Brand $brand)
    {
        $validator = Validator::make($req->all(), [
            'name' => ['required' , 'unique:brands,name,' . $brand->id]
        ], $this->messages);
        if($validator->fails()) {
            return response()->json([
                'error' => true,
                'errors' => $validator->errors()
            ]);
        }

        $updated = $brand->update([
            'name' => $req->name
        ]);

        return $updated
            ? response()->json([ 'success' => true, 'msg' => __('Marca actualizada correctamente.') ])
            : response()->json([ 'error' => true, 'msg' => __('Marca no se ha podido actualizar, pruébelo de nuevo más tarde.') ]);

        return $validator->errors();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        $this->authorize('brands.destroy');
        $brand->update([
            'deleted_by' => auth()->user()->id
        ]);
        $deleted = $brand->delete();

        return $deleted
            ? response()->json([ 'success' => true, 'msg' => 'Marca eliminada correctamente.'])
            : response()->json([ 'error' => true, 'msg' => 'Marca no se ha podido eliminar, pruébelo de nuevo más tarde.']);
    }

    public function get_all_brands() {
        return response()->json([
            'success' => true,
            'brands' => Brand::all()->toArray()
        ]);
    }
}
