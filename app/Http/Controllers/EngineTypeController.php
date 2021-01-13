<?php

namespace App\Http\Controllers;

use App\Models\EngineType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EngineTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $engine_types = EngineType::when($req->name, function(Builder $q, $name){
                $q->where('name', 'LIKE', $name . '%');
            })
            // COUNT OF TICKETS PER ENGINE TYPE
            ->withCount('tickets')
            ->orderBy('name', 'ASC')
            ->paginate();

        return response()->json([
            'engine_types' => $engine_types
        ]);
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
        $messages = [
            'name.required' => ':attribute es obligatorio.',
            'name.string' => ':attribute debe ser una cadena de texto.',
            'name.max' => ':attribute no puede tener una longitud mayor a :max',
        ];
        
        $validator = Validator::make($req->all(), [
            'name' => ['required', 'string', 'max:100']
        ], $messages);
        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        $created = EngineType::create([
            'name' => $req->name
        ]);

        return $created
            ?   response()->json(['success' => __('Tipo de motor creado correctamente!')])
            :   response()->json(['error' => __('Tipo de motor no se ha podido crear correctamente!')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EngineType  $engineType
     * @return \Illuminate\Http\Response
     */
    public function show(EngineType $engineType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EngineType  $engineType
     * @return \Illuminate\Http\Response
     */
    public function edit(EngineType $engineType)
    {
        $this->authorize('engine_types.update');

        return view('engine_types.edit', compact($engineType));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EngineType  $engineType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, EngineType $engineType)
    {
        $this->authorize('engine_type.update');

        $messages = [
            'name.required' => ':attribute es obligatorio.',
            'name.string' => ':attribute debe ser una cadena de texto.',
            'name.max' => ':attribute no puede tener una longitud mayor a :max',
        ];
        
        $validator = Validator::make($req->all(), [
            'name' => ['required', 'string', 'max:100']
        ], $messages);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        $updated = $engineType->update([
            'name' => $req->name
        ]);

        return $updated
            ?   response()->json(['success' => __('Tipo de motor creado correctamente!')])
            :   response()->json(['error' => __('Tipo de motor no se ha podido actualizar, vuelva a intentarlo mas tarde!')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EngineType  $engineType
     * @return \Illuminate\Http\Response
     */
    public function destroy(EngineType $engineType)
    {
        $this->authorize('engine_type.destroy');

        $deleted = $engineType->delete();

        return $deleted
            ?   response()->json(['success' => __('Tipo de motor creado correctamente!')])
            :   response()->json(['error' => __('Tipo de motor no se ha podido eliminar, vuelva a intentarlo mas tarde!')]);
    }
}
