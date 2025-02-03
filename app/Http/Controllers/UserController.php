<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\User\UserRepositoryInterface;
use App\Models\User;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json($this->userRepository->getAll());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $this->userRepository->create($request->all());
        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $users = $this->userRepository->findById($id);

        if(!empty($users)){
            return response()->json([
                'users' => $users
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
        $users = $this->userRepository->findById($id);

        if (!$users) {
            return response()->json([
                'message' => 'El usuario no se ha encontrado'
            ], 404); 
        }

        $updateUser = $this->userRepository->update($id, $request->all());

        return response()->json([
            "message" => "El usuario ha sido actualizado correctamente"
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $users = $this->userRepository->delete($id);

        if (!$users) {
            return response()->json([
                'message' => 'El usuario no se ha encontrado'
            ], 404); 
        }

        return response()->json([
            "message" => "El usuario ha sido borrado correctamente"
        ], 200);
    }

    public function setRegistrationType(Request $request, $id){
        $users = $this->userRepository->findById($id);

        if (!$users) {
            return response()->json([
                'message' => 'El usuario no se ha encontrado'
            ], 404); 
        }
        
        $this->userRepository->update($id, ['registration_type' => $request->registration_type]);

        return response()->json([
            "message" => "El usuario ha sido actualizado correctamente"
        ], 200);
    }
}
