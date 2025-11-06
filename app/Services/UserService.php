<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

Class UserService { 
    public function __construct(
        private UserRepository $userRepository
    ){}

    public function createUser(array $data): array{
        if($this->userRepository->findByEmail($data['email'])){
            throw ValidationException::withMessages(['email' => ['O email já está em uso']]);
        }

        $data['password'] = Hash::make($data['password']);
        $user = $this->userRepository->create($data);

        return [
            'user' => $user,
            'message' => 'Usuáio criado com sucessso!'
        ];
    }
}