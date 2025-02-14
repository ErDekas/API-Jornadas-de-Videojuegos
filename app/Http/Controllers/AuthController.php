<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Resgiter a new user
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'registration_type' => 'required|string|in:virtual,presential,student',
            'is_admin' => 'boolean',
        ]);
    
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->registration_type = $request->registration_type;
        $user->is_admin = $request->is_admin ?? false; // Por defecto, no admin
        $user->email_verified_at = null; // No verificado al inicio
        $user->student_verified = $request->registration_type === 'Estudiante';
    
        $user->save();

        // Crear token de autenticación con Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;
    
        return response()->json([
            'message' => 'El usuario ha sido registrado correctamente',
            'registration_type' => $user->registration_type,
            'student_verified' => $user->student_verified,
            'token' => $token,
            'user' => $user
        ],201);
    }

    /**
     * Log in with a email and password
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Las credenciales introducidas no son correctas'], 401);
        }

        if (!$user->email_verified_at) {
            return response()->json(['message' => 'El correo debe estar verificado antes de iniciar sesión']);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'El inicio de sesión ha salido correctamente',
            'token' => $token,
            'user' => $user
        ],200);
    }

    /**
     * Log out in the account of the API
     */
    public function logout(Request $request){
        $request->user()->tokens->each(function ($token){
            $token->delete();
        });

        return response()->json([
            'message' => 'Se ha cerrado sesión correctamente'
        ],200);
    }

     /**
     * Verify the email of one user before log in
     */
    public function verifyEmail(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'El usuario no se ha encontrado']);
        }

        if ($user->email_verified_at) {
            return response()->json(['message' => 'Este email ya está verificado']);
        }
    
        // Marcar como verificado
        $user->email_verified_at = now();
        $user->save();
    
        return response()->json([
            'message' => 'El correo ha sido verificado correctamente',
            'user' => $user
        ], 200);    
    }

    
}
