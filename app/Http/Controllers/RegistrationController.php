<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Repositories\Registration\RegistrationRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{

    protected $registrationRepository;

    public function __construct(RegistrationRepositoryInterface $registrationRepository)
    {
        $this->registrationRepository = $registrationRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $registrations = $this->registrationRepository->getAll();
        return response()->json([
            'data_count' => $registrations->count(),
            'registrations' => $registrations     
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->is_admin) {
            return response()->json([
                'message' => 'No tienes permiso para realizar esta acción'
            ], 403);
        }

        $validateData = $request->validate([
            'user_id' => 'required|exists:users,id',  
            'registration_type' => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:0.01', 
            'payment_status' => 'required|string|in:pending,completed,failed', 
            'ticket_code' => 'required|string|max:255|unique:registrations,ticket_code', 
        ]);

        $registration = $this->registrationRepository->create($validateData);

        return response()->json([
            "message" => "La inscripción ha sido agregada correctamente"
        ], 201);
        
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $registrations = $this->registrationRepository->findById($id);

        if(!empty($registrations)){
            return response()->json([
                'registrations' => $registrations
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
        if (!Auth::user()->is_admin) {
            return response()->json([
                'message' => 'No tienes permiso para realizar esta acción'
            ], 403);
        }

        $validateData = $request->validate([
            'user_id' => 'required|exists:users,id',  
            'registration_type' => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:0.01', 
            'payment_status' => 'required|string|in:pending,completed,failed', 
            'ticket_code' => 'required|string|max:255|unique:registrations,ticket_code', 
        ]);

        $registrations = $this->registrationRepository->update($id, $request->all());

        if (!$registrations) {
            return response()->json([
                'registrations' => 'La inscripcion no se ha encontrado'
            ], 404); 
        }

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
        if (!Auth::user()->is_admin) {
            return response()->json([
                'message' => 'No tienes permiso para realizar esta acción'
            ], 403);
        }

        $registrations = $this->registrationRepository->delete($id);

        if (!$registrations) {
            return response()->json([
                'message' => 'La inscripcion no se ha encontrado'
            ], 404); 
        }

        return response()->json([
            "message" => "La inscripción ha sido borrada correctamente"
        ], 200);
    }

    /**
     * Method to get all the registrations with a specific ticket
     */
    public function getTicket($id){
        $ticketCode = $this->registrationRepository->findByTicketCode($id);
  
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
            'message' => 'El ticket se ha encontrado'
        ], 200);
    }
}
