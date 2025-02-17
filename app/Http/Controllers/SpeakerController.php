<?php

namespace App\Http\Controllers;

use App\Models\Speaker;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Speaker\SpeakerRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        if (!Auth::user()->is_admin) {
            return response()->json([
                'message' => 'No tienes permisos para realizar esta acción'
            ], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'photo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'social_links' => 'nullable',
            'social_links.*' => 'nullable|url',
            'expertise_areas' => 'nullable|array',
        ]);

        $data = $request->all();

        // Manejar la subida del archivo
        if ($request->hasFile('photo_url')) {
            $file = $request->file('photo_url');
            $path = $file->store('images', 'public'); // Guarda en storage/app/public/speakers
            $data['photo_url'] = url('storage/' . $path); // Guarda la URL completa
        }

        $this->speakerRepository->create($data);

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

        $eventSpeaker = $speakers->events;

        if (!empty($speakers)) {
            return response()->json([
                'speaker' => $speakers,
                'eventSpeaker' => $eventSpeaker
            ], 200);
        } else {
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
        if (!Auth::user()->is_admin) {
            return response()->json([
                'message' => 'No tienes permisos para realizar esta acción'
            ], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'photo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'social_links' => 'nullable',
            'social_links.*' => 'nullable|url',
            'expertise_areas' => 'nullable|array',
        ]);

        $data = $request->except('photo_url');

        // Manejar la subida del archivo
        if ($request->hasFile('photo_url')) {
            $file = $request->file('photo_url');
            $path = $file->store('speakers', 'public');
            $data['photo_url'] = url('storage/' . $path);

            // Opcional: Eliminar la imagen anterior
            $speaker = $this->speakerRepository->findById($id);
            if ($speaker && $speaker->photo_url) {
                $oldPath = str_replace(url('storage/'), '', $speaker->photo_url);
                Storage::disk('public')->delete($oldPath);
            }
        }

        $speaker = $this->speakerRepository->update($id, $data);

        if (!$speaker) {
            return response()->json([
                'message' => 'El ponente no se ha encontrado'
            ], 404);
        }

        return response()->json([
            "message" => "El ponente ha sido actualizado correctamente",
            "speaker" => $speaker
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (!Auth::user()->is_admin) {
            return response()->json([
                'message' => 'No tienes permisos para realizar esta acción'
            ], 403);
        }

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
