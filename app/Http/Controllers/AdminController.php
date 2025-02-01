<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payment;
use App\Models\Registration;

class AdminController extends Controller
{
    /**
     * Method to get the attendees
     */
    public function listAttendee()
    {
        $attendees = User::where('is_admin', false)->get();

        return response()->json([
            'message' => 'La lista de asistentes ha sido obtenida con éxito',
            'attendees' => $attendees
        ]);
    }

    /**
     * Method to get the payments
     */
    public function listPayments()
    {
        $payments = Payment::all();

        return response()->json([
            'message' => 'La lista de pagos obtenida con éxito',
            'payments' => $payments
        ]);
    }

    /**
     * Method to get the stadistics
     */
    public function getStatistics()
    {
        $totalUsers = User::count();
        $totalAttendees = User::where('is_admin', false)->count();
        $totalAdmins = User::where('is_admin', true)->count();
        $totalPayments = Payment::sum('amount');
        $totalRegistrations = Registration::count();

        return response()->json([
            'message' => 'Las estadísticas han sido obtenidas con éxito',
            'total_users' => $totalUsers,
            'total_attendees' => $totalAttendees,
            'total_admins' => $totalAdmins,
            'total_payments' => $totalPayments,
            'total_registrations' => $totalRegistrations
        ], 200);
    }
}
