<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Repositories\Event\EventRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    protected $eventRepository;
    protected $userRepository;

    public function __construct(EventRepositoryInterface $eventRepository, UserRepositoryInterface $userRepository)
    {
        $this->eventRepository = $eventRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = $this->eventRepository->getAll();
        return response()->json([
            'data_count' => $events->count(),
            'events' => $events
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

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string',
            'date' => 'required|date',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'max_attendees' => 'required|integer',
            'current_attendees' => 'required|integer',
            'location' => 'required|string|max:255',
        ]);

        $events = $this->eventRepository->create($validatedData);

        return response()->json([
            "message" => "El evento ha sido agregado correctamente",
            'data_count' => 1
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $events = $this->eventRepository->findById($id);

        if (!empty($events)) {
            return response()->json([
                'event' => $events
            ], 200);
        } else {
            return response()->json([
                "message" => "El evento no se ha encontrado"
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

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'max_attendees' => 'required|integer',
            'current_attendees' => 'required|integer',
            'location' => 'required|string|max:255',
        ]);

        $events = $this->eventRepository->update($id, $validatedData);

        if (!$events) {
            return response()->json([
                'message' => 'El evento no se ha encontrado'
            ], 404);
        }

        return response()->json([
            "message" => "El evento ha sido actualizado correctamente"
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

        $events = $this->eventRepository->delete($id);

        if (!$events) {
            return response()->json([
                'message' => 'El evento no se ha encontrado'
            ], 404);
        }

        return response()->json([
            "message" => "El evento ha sido borrado correctamente",
            'data_count' => 0
        ], 200);
    }

    /**
     * Method to check the availability of the event
     */
    public function checkAvailability($id)
    {
        $event = $this->eventRepository->findById($id);

        if (!$event) {
            return response()->json([
                'message' => 'El evento no se ha encontrado'
            ], 404);
        }

        $availability = $event->max_attendees - $event->current_attendees;

        return response()->json([
            'event_id' => $event->id,
            'title' => $event->title,
            'available_slots' => $availability > 0 ? $availability : 0,
            'status' => $availability > 0 ? 'Quedan plazas' : 'No hay plazas'
        ], 200);
    }

    /**
     * Method to register an attendee to an event
     */
    public function registerAttendee(Request $request, $id)
    {
        // Buscar el evento
        $event = $this->eventRepository->findById($id);

        if (!$event) {
            return response()->json([
                'message' => 'El evento no se ha encontrado'
            ], 404);
        }

        // Obtener el usuario autenticado (o desde la solicitud si viene como parámetro)
        $userId = $request->user()->id ?? $request->input('user_id');

        // Verificar si el usuario ya está registrado
        if ($event->attendees()->where('user_id', $userId)->exists()) {
            return response()->json([
                'message' => 'El usuario ya está registrado en este evento'
            ], 409);
        }

        // Registrar al usuario en el evento
        $event->attendees()->attach($userId);

        $user = $this->userRepository->findById($userId);

        if($user['registration_type'] !== 'virtual'){
            $event->increment('current_attendees');
        }

        return response()->json([
            'message' => 'Registro exitoso',
            'event_id' => $event->id
        ], 201);
    }
}
