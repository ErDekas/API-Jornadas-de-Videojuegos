<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $registrations = Registration::all();
        return response()->json([
            'registrations' => $registrations,
            'data_count' => $registrations->count()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',  
            'registration_type' => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:0.01', 
            'payment_status' => 'required|string|in:pending,completed,failed', 
            'ticket_code' => 'required|string|max:255|unique:registrations,ticket_code', 
        ]);

        $registrations = new Registration;

        $registrations->user_id = $request->user_id;
        $registrations->registration_type = $request->registration_type;
        $registrations->total_amount = $request->total_amount;
        $registrations->payment_status = $request->payment_status;
        $registrations->ticket_code = $request->ticket_code;

        $registrations->save();

        return response()->json([
            "message" => "La inscripción ha sido agregada correctamente",
            'data_count' => 1 
        ], 201);
        
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $registrations = Registration::find($id);

        if(!empty($registrations)){
            return response()->json([
                'registrations' => $registrations,
                'data_count' => 1
            ], 200);
        }
        else{
            return response()->json([
                "message" => "La inscripción no se ha encontrado"
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $registrations = Registration::find($id);

        if (!$registrations) {
            return response()->json([
                'registrations' => 'La inscripcion no se ha encontrado'
            ], 404); 
        }

        $registrations->user_id = $request->regisuser_idtration_id;
        $registrations->registration_type = $request->registration_type;
        $registrations->total_amount = $request->total_amount;
        $registrations->payment_status = $request->payment_status;
        $registrations->ticket_code = $request->ticket_code;

        $registrations->save();

        return response()->json([
            "message" => "La inscripción ha sido actualizada correctamente",
            'data_count' => 1 
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $registrations = Registration::find($id);

        if (!$registrations) {
            return response()->json([
                'message' => 'La inscripcion no se ha encontrado'
            ], 404); 
        }

        $registrations->delete();

        return response()->json([
            "message" => "La inscripción ha sido borrada correctamente",
            'data_count' => 0 
        ], 200);
    }

    /**
     * Method to get all the registrations with a specific ticket
     */
    public function getTicket($id){
        $ticketCode = Registration::where('ticket_code', $id)->first();
  
        if (!$ticketCode) {
            return response()->json([
                'message' => 'No hay inscripciones con este ticket'
            ], 404);
        }
    
        return response()->json([
            'ticket_code' => $ticketCode->ticket_code,
            'user_id' => $ticketCode->user_id,
            'registration_type' => $ticketCode->registration_type,
            'total_amount' => $ticketCode->total_amount,
            'payment_status' => $ticketCode->payment_status,
            'message' => 'El ticket se ha encontrado',
            'data_count' => 1
        ], 200);
    }
}
