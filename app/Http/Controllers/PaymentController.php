<?php

namespace App\Http\Controllers;

use App\Repositories\Payment\PaymentRepositoryInterface;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\PaymentRequest;

class PaymentController extends Controller
{

    protected $paymentRepository;

    public function __construct(PaymentRepositoryInterface $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = $this->paymentRepository->getAll();
        return response()->json([
            'data_count' => $payments->count(),
            'payments' => $payments
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PaymentRequest $request)
    {
        if (!Auth::user()->is_admin) {
            return response()->json([
                'message' => 'No tienes permisos para realizar esta acción'
            ], 403);
        }

        $payment = $this->paymentRepository->create($request->validated());

        return response()->json([
            "message" => "El pago ha sido agregado correctamente",
            "payment" => $payment
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $payments = $this->paymentRepository->findById($id);

        if (!empty($payments)) {
            return response()->json([
                'payment' => $payments
            ], 200);
        } else {
            return response()->json([
                "message" => "El pago no se ha encontrado"
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PaymentRequest $request, $id)
    {
        if (!Auth::user()->is_admin) {
            return response()->json([
                'message' => 'No tienes permisos para realizar esta acción'
            ], 403);
        }

        $payments = $this->paymentRepository->update($id, $request->validated());

        if (!$payments) {
            return response()->json([
                'message' => 'El pago no se ha encontrado'
            ], 404);
        }

        return response()->json([
            "message" => "El pago ha sido actualizado correctamente"
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

        $payments = $this->paymentRepository->delete($id);

        if (!$payments) {
            return response()->json([
                'message' => 'El pago no se ha encontrado'
            ], 404);
        }

        return response()->json([
            "message" => "El pago ha sido borrado correctamente"
        ], 200);
    }

    /**
     * Method for process the payment
     */
    public function process(PaymentRequest $request)
    {
        $validated = $request->validated();

        $payment = $this->paymentRepository->process([
            'registration_id' => $validated['registration_id'],
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'transaction_id' => $validated['transaction_id'],
            'status' => $validated['status'],
            'paypal_order_id' => $validated['paypal_order_id'] ?? null,
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
    public function verify(Request $request)
    {

        $request->validate([
            'transaction_id' => 'nullable|string|exists:payments,transaction_id',
            'paypal_order_id' => 'nullable|string|exists:payments,paypal_order_id',
        ]);

        $payment = $this->paymentRepository->findByTransactionOrPaypalOrderId($request->transaction_id, $request->paypal_order_id);

        if (!$payment) {
            return response()->json([
                'message' => 'El pago no se ha encontrado'
            ], 404);
        }

        return response()->json([
            'message' => 'Pago encontrado',
            'payment' => $payment
        ], 200);
    }
}
