<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Services\UserService;
use Exception;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ){}

    public function store(CreateUserRequest $request){
        try{
            $result = $this->userService->createUser($request->validated());
            return response()->json($result, 201);
        }
        catch(Exception $e){
            return response()->json(['error' => 'Erro interno do servidor'], 500);
        }
    }
}
