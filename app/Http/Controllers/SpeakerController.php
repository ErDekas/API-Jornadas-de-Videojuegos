<?php

namespace App\Http\Controllers;

use App\Models\Speaker;
use App\Repositories\Speaker\SpeakerRepositoryInterface;
use Illuminate\Http\Request;

class SpeakerController extends Controller
{

    protected $speakerRepository;

    public function __construct(SpeakerRepositoryInterface $speakerRepository)
    {
        $this->speakerRepository = $speakerRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $speakers = $this->speakerRepository->getAll();
        return response()->json([
            'data_count' => $speakers->count(),
            'speakers' => $speakers
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255', 
            'photo_url' => 'nullable|url', 
            'social_links' => 'nullable|array',
            'social_links.*' => 'nullable|url',
            'exprestise_areas' => 'nullable|string', 
        ]);

        $this->speakerRepository->create($request->all());

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
        $speakers = $this->speakerRepository->findById($id);

        if(!empty($speakers)){
            return response()->json([
                'speaker' => $speakers
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
        $request->validate([
            'name' => 'required|string|max:255', 
            'photo_url' => 'nullable|url', 
            'social_links' => 'nullable|array',
            'social_links.*' => 'nullable|url',
            'exprestise_areas' => 'nullable|string',
        ]);

        $speakers = $this->speakerRepository->update($id, $request->all());

        if (!$speakers) {
            return response()->json([
                'message' => 'El ponente no se ha encontrado'
            ], 404); 
        }

        return response()->json([
            "message" => "El ponente ha sido actualizado correctamente"
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $speakers = $this->speakerRepository->delete($id);

        if (!$speakers) {
            return response()->json([
                'message' => 'El ponente no se ha encontrado'
            ], 404); 
        }

        return response()->json([
            "message" => "El ponente ha sido borrado correctamente"
        ], 200);
    }
}
