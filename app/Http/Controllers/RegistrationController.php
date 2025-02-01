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
        return response()->json($registrations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $registrations = new Registration;

        $registrations->user_id = $request->user_id;
        $registrations->registration_type = $request->registration_type;
        $registrations->total_amount = $request->total_amount;
        $registrations->payment_status = $request->payment_status;
        $registrations->ticket_code = $request->ticket_code;

        return response()->json([
            "message" => "La inscripción ha sido agregada correctamente"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $registrations = Registration::find($id);

        if(!empty($registrations)){
            return response()->json($registrations);
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

        $registrations->user_id = $request->regisuser_idtration_id;
        $registrations->registration_type = $request->registration_type;
        $registrations->total_amount = $request->total_amount;
        $registrations->payment_status = $request->payment_status;
        $registrations->ticket_code = $request->ticket_code;

        return response()->json([
            "message" => "La inscripción ha sido actualizada correctamente"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $registrations = Registration::find($id);
        $registrations->delete();

        return response()->json([
            "message" => "La inscripción ha sido borrada correctamente"
        ]);
    }
}
