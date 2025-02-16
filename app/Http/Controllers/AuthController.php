<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        ], 201);
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
            return response()->json(['message' => 'El correo debe estar verificado antes de iniciar sesión'], 402);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'El inicio de sesión ha salido correctamente',
            'token' => $token,
            'user' => $user
        ], 200);
    }

    /**
     * Log out in the account of the API
     */
    public function logout(Request $request)
    {
        $request->user()->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json([
            'message' => 'Se ha cerrado sesión correctamente'
        ], 200);
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

    /**
     * Iniciar el proceso de restablecimiento de contraseña
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'No encontramos un usuario con ese correo electrónico'
            ], 404);
        }

        // Generar token único
        $token = Str::random(64);

        // Guardar el token en la base de datos
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        return response()->json([
            'message' => 'Se ha enviado un enlace de restablecimiento a tu correo electrónico',
            'success' => true,
            'token' => $token,
            'user' => [
                'name' => $user->name,
                'email' => $user->email
            ]
        ]);
    }

    /**
     * Verificar si el token de restablecimiento es válido
     */
    public function verifyResetToken(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email'
        ]);

        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$resetRecord) {
            return response()->json([
                'message' => 'Token inválido',
                'success' => false
            ], 400);
        }

        // Verificar si el token ha expirado (24 horas)
        $createdAt = Carbon::parse($resetRecord->created_at);
        if ($createdAt->addHours(24)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return response()->json([
                'message' => 'El token ha expirado',
                'success' => false
            ], 400);
        }

        return response()->json([
            'message' => 'Token válido',
            'success' => true
        ]);
    }

    /**
     * Restablecer la contraseña
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed'
        ]);

        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$resetRecord) {
            return response()->json([
                'message' => 'Token inválido',
                'success' => false
            ], 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'No se encontró el usuario',
                'success' => false
            ], 404);
        }

        // Actualizar contraseña
        $user->password = Hash::make($request->password);
        $user->save();

        // Eliminar el token usado
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        
        return response()->json([
            'message' => 'Contraseña actualizada correctamente',
            'success' => true
        ]);
    }
}