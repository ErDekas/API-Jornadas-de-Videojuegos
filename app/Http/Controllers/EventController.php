<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::all();
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
        $events = new Event;
        
        $events->title = $request->title;
        $events->description = $request->description;
        $events->type = $request->type;
        $events->date = $request->date;
        $events->start_time = $request->start_time;
        $events->end_time = $request->end_time;
        $events->max_attendees = $request->max_attendees;
        $events->current_attendees = $request->current_attendees;
        $events->location = $request->location;

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
        $events = Event::find($id);

        if(!empty($events)){
            return response()->json([
                'event' => $events,
                'data_count' => 1
            ], 200);
        }
        else{
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
        $events = Event::find($id);

        if (!$events) {
            return response()->json([
                'message' => 'El evento no se ha encontrado'
            ], 404); 
        }

        $events->title = $request->title;
        $events->description = $request->description;
        $events->type = $request->type;
        $events->date = $request->date;
        $events->start_time = $request->start_time;
        $events->end_time = $request->end_time;
        $events->max_attendees = $request->max_attendees;
        $events->current_attendees = $request->current_attendees;
        $events->location = $request->location;

        return response()->json([
            "message" => "El evento ha sido actualizado correctamente",
            'data_count' => 1
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $events = Event::find($id);
        return response()->json([
            'message' => 'El evento no se ha encontrado'
        ], 404);
        $events->delete();

        return response()->json([
            "message" => "El evento ha sido borrado correctamente",
            'data_count' => 0 
        ], 200);
    }

    /**
     * Method to check the availability of the event
     */
    public function checkAvailability($id){
        $event = Event::find($id);

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
            'status' => $availability > 0 ? 'Quedan plazas' : 'No hay plazas',
            'data_count' => 1
        ], 200);
    }

    /**
     * Method to see the attendee of the event
     */
    public function registerAttendee($id){
        $event = Event::find($id);

        if (!$event) {
            return response()->json([
                'message' => 'El evento no se ha encontrado'
            ], 404);
        }

        $attendees = $event->attendees()->select('users.id', 'users.name', 'users.email')->get();

        if ($attendees->isEmpty()) {
            return response()->json([
                'message' => 'Este evento no tiene asistentes registrados',
                'data_count' => 0 
            ], 200);
        }

        return response()->json([
            'data_count' => $attendees->count(),
            'event_id' => $event->id,
            'title' => $event->title,
            'attendees' => $attendees
        ], 200);
    }
}
