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
        return response()->json($events);
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
            "message" => "El evento ha sido agregado correctamente"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $events = Event::find($id);

        if(!empty($events)){
            return response()->json($events);
        }
        else{
            return response()->json([
                "message" => "El evento no se ha encontrado"
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $events = Event::find($id);

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
            "message" => "El evento ha sido actualizado correctamente"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $events = Event::find($id);
        $events->delete();

        return response()->json([
            "message" => "El evento ha sido borrado correctamente"
        ]);
    }
}
