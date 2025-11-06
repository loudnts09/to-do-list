<?php

namespace App\Http\Controllers;

use Exception;
use App\Services\UserAuthService;
use App\Http\Requests\AuthUserRequest;

class AuthController extends Controller
{
    public function __construct(
        private UserAuthService $userAuthService
    ){}

    public function showLogin(){
        return view('login');
    }

    public function login(AuthUserRequest $request) {

        $loginOk = $this->userAuthService->login($request->only(['email', 'password']));

        if($loginOk){
            $request->session()->regenerate();
            return redirect()->route('restrict.home');
        }
        
        return redirect()->back()->withErrors(['error' => 'Credenciais inválidas.',])->onlyInput('email');
    }

    public function logout(){
        try {
            $this->userAuthService->logout();
            return redirect()->back()->withErrors(['success' => 'Logout realizado com sucesso.',])->onlyInput('email');
        } 
        catch (Exception $e) {

            return redirect()->back()->withErrors(['error' => 'Erro ao fazer logout.',])->onlyInput('email');
        }
    }
}
