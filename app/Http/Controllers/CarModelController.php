<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\CarModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CarModelController extends Controller
{
    private $messages = [
        'required' => ':attribute es un campo requerido.',
        'max' => ':attribute no puede contener mas de :max caracteres.',
        'string' => ':attribute debe ser una cadena de texto.',
        'exists' => ':attribute existir en la tabla de marcas.',
        'unique' => ':attribute debe ser único, el texto que está introduciendo ya existe.',
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        if($req->ajax()) {
            $car_models = CarModel::when($req->brand_id, function(Builder $q, $brand_id) {
                $q->whereHas('brand', function(Builder $q2) use ($brand_id) {
                    $q2->where('brands.id', $brand_id);
                });
            })->when($req->name, function(Builder $q, $name) {
                $q->where('car_models.name', 'LIKE', '%' . $name . '%');
            })->with('brand')
            ->orderBy('car_models.brand_id', 'ASC')
            ->orderBy('car_models.name', 'ASC')
            ->paginate();
            
            return response()->json([
                'success' => true,
                'carModels' => $car_models
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
            'brand_id' => ['required', 'exists:brands,id'],
            'name' => ['required', 'string', 'max:255', 'unique:car_models,name']
        ], $this->messages);
        if($validator->fails()) {
            return response()->json([
                'error' => true,
                'errors' => $validator->errors()
            ]);
        }

        $created = CarModel::create([
            'name' => $req->name
        ]);
        $get_brand = Brand::find($req->brand_id);
        $created->brand()->associate($get_brand->id);

        $created->save();
        
        return $created 
            ? response()->json([ 'success' => true, 'msg' => __('Modelo creado correctamente.')])
            : response()->json([ 'error' => true, 'msg' => __('El modelo no se ha podido crear, pruébelo de nuevo más tarde.')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CarModel  $carModel
     * @return \Illuminate\Http\Response
     */
    public function show(CarModel $carModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CarModel  $carModel
     * @return \Illuminate\Http\Response
     */
    public function edit(CarModel $carModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CarModel  $carModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CarModel $carModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CarModel  $carModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(CarModel $carModel)
    {
        // $this->authorize('car_models.destroy');

        $carModel->update([
            'deleted_by' => auth()->user()->id
        ]);

        $deleted = $carModel->delete();

        return $deleted
            ? response()->json([ 'success' => true, 'msg' => 'Marca eliminada correctamente'])
            : response()->json([ 'error' => true, 'msg' => 'Marca no se ha podido eliminar, pruébelo de nuevo más tarde.']);
    }

    public function get_brand_models(Brand $brand) {
        return response()->json([
            'models' => $brand->models->toArray()
        ]);
    }

    public function get_car_models_counter() {
        $brands_count = Brand::count();

        return response()->json([
            'success' => true,
            'brands_count' => $brands_count
        ]);
    }
}
