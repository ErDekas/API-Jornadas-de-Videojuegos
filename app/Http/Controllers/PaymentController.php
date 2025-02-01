<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::all();
        return response()->json($payments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $payments = new Payment;

        $payments->registration_id = $request->registration_id;
        $payments->amount = $request->amount;
        $payments->payment_method = $request->payment_method;
        $payments->transaction_id = $request->transaction_id;
        $payments->status = $request->status;
        $payments->paypal_order_id = $request->paypal_order_id;

        return response()->json([
            "message" => "El pago ha sido agregado correctamente"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $payments = Payment::find($id);

        if(!empty($payments)){
            return response()->json($payments);
        }
        else{
            return response()->json([
                "message" => "El pago no se ha encontrado"
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $payments = Payment::find($id);

        $payments->registration_id = $request->registration_id;
        $payments->amount = $request->amount;
        $payments->payment_method = $request->payment_method;
        $payments->transaction_id = $request->transaction_id;
        $payments->status = $request->status;
        $payments->paypal_order_id = $request->paypal_order_id;

        return response()->json([
            "message" => "El pago ha sido actualizado correctamente"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $payments = Payment::find($id);
        $payments->delete();

        return response()->json([
            "message" => "El pago ha sido borrado correctamente"
        ]);
    }
}
