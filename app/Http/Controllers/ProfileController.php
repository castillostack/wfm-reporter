<?php

namespace App\Http\Controllers;

use App\Actions\Usuarios\ActualizarPerfilUsuarioAction;
use App\Actions\Usuarios\CambiarContrasenaUsuarioAction;
use App\Actions\Usuarios\ObtenerPerfilUsuarioAction;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller {
   public function show(ObtenerPerfilUsuarioAction $action): View {
      $usuario = $action->handle(auth()->id());

      return view('pages.profile', compact('usuario'));
   }

   public function edit(ObtenerPerfilUsuarioAction $action): View {
      $usuario = $action->handle(auth()->id());

      return view('pages.profile-edit', compact('usuario'));
   }

   public function update(UpdateProfileRequest $request, ActualizarPerfilUsuarioAction $action): RedirectResponse {
      $usuario = $action->handle(auth()->id(), $request->validated());

      return redirect()->route('profile.show')->with('success', 'Perfil actualizado correctamente.');
   }

   public function editPassword(): View {
      return view('pages.profile-password');
   }

   public function updatePassword(UpdatePasswordRequest $request, CambiarContrasenaUsuarioAction $action): RedirectResponse {
      $action->handle(auth()->id(), $request->current_password, $request->password);

      return redirect()->route('profile.show')->with('success', 'Contraseña actualizada correctamente.');
   }
}
