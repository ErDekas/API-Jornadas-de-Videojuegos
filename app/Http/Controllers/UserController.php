<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(User::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = User::create($request->all());
        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $users = User::find($id);

        if(!empty($users)){
            return response()->json([
                'users' => $users,
                'data_count' => 1
            ], 200);
        }
        else{
            return response()->json([
                "message" => "El usuario no se ha encontrado"
            ],404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $users = User::find($id);

        if (!$users) {
            return response()->json([
                'message' => 'El usuario no se ha encontrado'
            ], 404); 
        }

        $users->name = $request->name;
        $users->email = $request->email;
        $users->password = $request->password;
        $users->registration_type = $request->registration_type;
        $users->is_admin = $request->is_admin;
        $users->email_verified_at = $request->email_verified_at;
        $users->student_verified = $request->student_verified;

        $users->save();

        return response()->json([
            "message" => "El usuario ha sido actualizado correctamente",
            'data_count' => 1
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $users = User::find($id);

        if (!$users) {
            return response()->json([
                'message' => 'El usuario no se ha encontrado'
            ], 404); 
        }

        $users->delete();

        return response()->json([
            "message" => "El usuario ha sido borrado correctamente",
            'data_count' => 0 
        ], 200);
    }

    public function setRegistrationType(Request $request, $id){
        $users = User::find($id);

        if (!$users) {
            return response()->json([
                'message' => 'El usuario no se ha encontrado'
            ], 404); 
        }
        
        $users->registration_type = $request->registration_type;

        $users->save();

        return response()->json([
            "message" => "El usuario ha sido actualizado correctamente"
        ], 200);
    }
}
