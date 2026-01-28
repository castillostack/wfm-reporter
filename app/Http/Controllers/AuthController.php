<?php

namespace App\Http\Controllers;

use App\Actions\Usuarios\AutenticarUsuarioAction;
use App\Actions\Usuarios\CrearUsuarioAction;
use App\Actions\Usuarios\LogoutUsuarioAction;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {
    public function login() {
        return view('pages.auth.login');
    }

    public function authenticate(LoginRequest $request, AutenticarUsuarioAction $action) {
        $usuario = $action->handle($request->validated());

        if ($usuario) {
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Credenciales inválidas.',
        ]);
    }

    public function register() {
        return view('pages.auth.register');
    }

    public function store(RegisterRequest $request, CrearUsuarioAction $action) {
        $usuario = $action->handle($request->validated());

        Auth::login($usuario);

        return redirect()->route('dashboard');
    }

    public function logout(LogoutUsuarioAction $action) {
        $action->handle();

        return redirect()->route('login');
    }
}
