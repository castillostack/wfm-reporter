<?php

namespace App\Http\Controllers;

use App\Actions\Usuarios\ActualizarPerfilUsuarioAction;
use App\Actions\Usuarios\ObtenerPerfilUsuarioAction;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(ObtenerPerfilUsuarioAction $action): View
    {
        $usuario = $action->handle(auth()->id());

        return view('pages.profile', compact('usuario'));
    }

    public function update(UpdateProfileRequest $request, ActualizarPerfilUsuarioAction $action): RedirectResponse
    {
        $usuario = $action->handle(auth()->id(), $request->validated());

        return redirect()->route('profile.show')->with('success', 'Perfil actualizado correctamente.');
    }
}