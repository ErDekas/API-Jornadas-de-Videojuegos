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
        return response()->json([
            'payments' => $payments,
            'data_count' => $payments->count()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'registration_id' => 'required|exists:registrations,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|in:card,paypal',
            'transaction_id' => 'required|string|unique:payments,transaction_id',
            'status' => 'required|string|in:pending,completed,failed',
            'paypal_order_id' => 'nullable|string',
        ]);

        $payments = new Payment;

        $payments->registration_id = $request->registration_id;
        $payments->amount = $request->amount;
        $payments->payment_method = $request->payment_method;
        $payments->transaction_id = $request->transaction_id;
        $payments->status = $request->status;
        $payments->paypal_order_id = $request->paypal_order_id;

        $payments->save();


        return response()->json([
            "message" => "El pago ha sido agregado correctamente",
            'data_count' => 1 
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $payments = Payment::find($id);

        if(!empty($payments)){
            return response()->json([
                'payment' => $payments,
                'data_count' => 1
            ], 200);
        }
        else{
            return response()->json([
                "message" => "El pago no se ha encontrado"
            ],404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $payments = Payment::find($id);

        if (!$payments) {
            return response()->json([
                'message' => 'El pago no se ha encontrado'
            ], 404); 
        }

        $payments->registration_id = $request->registration_id;
        $payments->amount = $request->amount;
        $payments->payment_method = $request->payment_method;
        $payments->transaction_id = $request->transaction_id;
        $payments->status = $request->status;
        $payments->paypal_order_id = $request->paypal_order_id;

        $payments->save();

        return response()->json([
            "message" => "El pago ha sido actualizado correctamente",
            'data_count' => 1 
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $payments = Payment::find($id);

        if (!$payments) {
            return response()->json([
                'message' => 'El pago no se ha encontrado'
            ], 404); 
        }

        $payments->delete();

        return response()->json([
            "message" => "El pago ha sido borrado correctamente",
            'data_count' => 0 
        ], 200);
    }

    /**
     * Method for process the payment
     */
    public function process(Request $request){

        $validate = $request->validate([
            'registration_id' => 'required|exists:registrations,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|in:card,paypal',
            'transaction_id' => 'required|string|unique:payments,transaction_id',
            'status' => 'required|string|in:pending,completed,failed',
            'paypal_order_id' => 'nullable|string',
        ]);

        $payment = Payment::create([
            'registration_id' => $validate['registration_id'],
            'amount' => $validate['amount'],
            'payment_method' => $validate['payment_method'],
            'transaction_id' => $validate['transaction_id'],
            'status' => $validate['status'],
            'paypal_order_id' => $validate['paypal_order_id'] ?? null,
        ]);

        return response()->json([
            "message" => "El pago ha sido procesado correctamente",
            "payment" => $payment,
            'data_count' => 1 
        ], 201);

    }

    /**
     * Method for verify a payment
     */
    public function verify(Request $request){

        $request->validate([
            'transaction_id' => 'nullable|string|exists:payments,transaction_id',
            'paypal_order_id' => 'nullable|string|exists:payments,paypal_order_id',
        ]);

        $payment = Payment::where('transaction_id', $request->transaction_id)
                      ->orWhere('paypal_order_id', $request->paypal_order_id)
                      ->first();

        if (!$payment) {
            return response()->json([
                'message' => 'El pago no se ha encontrado'
            ], 404);
        }

        return response()->json([
            'message' => 'Pago encontrado',
            'payment' => $payment,
            'data_count' => 1 
        ], 200);

    }
}
