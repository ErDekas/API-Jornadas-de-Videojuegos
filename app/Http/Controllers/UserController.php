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
            return response()->json($users);
        }
        else{
            return response()->json([
                "message" => "El usuario no se ha encontrado"
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $users = User::find($id);

        $users->name = $request->name;
        $users->email = $request->email;
        $users->password = $request->password;
        $users->registration_type = $request->registration_type;
        $users->is_admin = $request->is_admin;
        $users->email_verified_at = $request->email_verified_at;
        $users->student_verified = $request->student_verified;

        return response()->json([
            "message" => "El usuario ha sido actualizado correctamente"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $users = User::find($id);
        $users->delete();

        return response()->json([
            "message" => "El usuario ha sido borrado correctamente"
        ]);
    }
}
