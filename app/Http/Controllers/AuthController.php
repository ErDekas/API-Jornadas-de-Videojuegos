<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

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
            return response()->json(['message' => 'El correo debe estar verificado antes de iniciar sesión']);
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


public function resetPassword(Request $request)
{
    // Validación del correo
    $user = User::where('email', $request->email)->first();
    if (!$user) {
        Log::warning('User not found for email: ' . $request->email);
        return response()->json(['message' => 'El usuario no se ha encontrado'], 404);
    }
    try {
        // Intentar enviar el enlace de restablecimiento de contraseña
        $response = Password::sendResetLink(
            $request->only('email')
        );

        // Verificar si el enlace fue enviado correctamente
        if ($response === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Se ha enviado un enlace para restablecer la contraseña a tu correo electrónico.'], 200);
        }

        // Si hubo un error (por ejemplo, el correo no está registrado o hay algún problema con el envío)
        Log::error('Error sending password reset link', [
            'email' => $request->email,
            'response' => $response
        ]);
        return response()->json(['message' => 'Hubo hubo  huboo un problema al enviar el enlace de restablecimiento de contraseña.'], 400);
        
    } catch (ValidationException $e) {
        // Capturar errores de validación específicos, como correo inválido
        Log::error('Validation error when trying to send password reset link', [
            'email' => $request->email,
            'error' => $e->getMessage()
        ]);
        return response()->json(['message' => 'El correo electrónico proporcionado es inválido.'], 422);
    } catch (\Exception $e) {
        // Capturar cualquier otro tipo de excepción
        Log::error('Unexpected error when trying to send password reset link', [
            'email' => $request->email,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        //return response()->json(['message' => 'Hubo hubo un problema al procesar la solicitud. Por favor, intenta nuevamente más tarde.'], 500);
        return response()->json(['message' => $e->getMessage()], 500);

    }
}


    /**
     * Update the password when an User didn't remember it
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'La contraseña actual no es correcta'], 403);
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        return response()->json(['message' => 'Contraseña actualizada correctamente'], 200);
    }
}
