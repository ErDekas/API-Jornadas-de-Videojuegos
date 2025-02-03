<?php

namespace App\Http\Controllers;

use App\Models\Speaker;
use Illuminate\Http\Request;

class SpeakerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $speakers = Speaker::all();
        return response()->json([
            'speakers' => $speakers,
            'data_count' => $speakers->count() 
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $speakers = new Speaker;

        $speakers->name = $request->name;
        $speakers->photo_url = $request->photo_url;
        $speakers->social_links = $request->social_links;
        $speakers->exprestise_areas = $request->exprestise_areas;

        return response()->json([
            "message" => "El ponente ha sido agregado correctamente",
            'data_count' => 1 
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $speakers = Speaker::find($id);

        if(!empty($speakers)){
            return response()->json([
                'speaker' => $speakers,
                'data_count' => 1
            ], 200);
        }
        else{
            return response()->json([
                "message" => "El ponente no se ha encontrado"
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $speakers = Speaker::find($id);

        if (!$speakers) {
            return response()->json([
                'message' => 'El ponente no se ha encontrado'
            ], 404); 
        }

        $speakers->name = $request->name;
        $speakers->photo_url = $request->photo_url;
        $speakers->social_links = $request->social_links;
        $speakers->exprestise_areas = $request->exprestise_areas;

        return response()->json([
            "message" => "El ponente ha sido actualizado correctamente",
            'data_count' => 1
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $speakers = Speaker::find($id);

        if (!$speakers) {
            return response()->json([
                'message' => 'El ponente no se ha encontrado'
            ], 404); 
        }

        $speakers->delete();

        return response()->json([
            "message" => "El ponente ha sido borrado correctamente",
            'data_count' => 0 
        ], 200);
    }
}
