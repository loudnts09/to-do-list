<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

Class UserAuthService { 
    public function login(array $credentials): bool{
        return  Auth::attempt($credentials);
    }

    public function logout() {
        return Auth::logout();
    }

}