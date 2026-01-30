<?php

namespace App\Http\Controllers;

use App\Actions\Equipos\ActualizarEquipoAction;
use App\Actions\Equipos\CrearEquipoAction;
use App\Actions\Equipos\DesactivarEquipoAction;
use App\Http\Requests\CreateTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamManagementController extends Controller {
    public function __construct() {
        //
    }

    public function index(Request $request): View {
        // Verificar que el usuario tenga el rol requerido
        if (!auth()->user()->hasRole('analista-wfm')) {
            abort(403, 'Acceso denegado. Se requiere rol de Analista WFM.');
        }

        $busqueda = $request->get('search');

        $equipos = Team::with(['lider', 'departamento'])
            ->withCount('empleados')
            ->when($busqueda, function ($query) use ($busqueda) {
                $query->where('name', 'like', "%{$busqueda}%")
                    ->orWhere('description', 'like', "%{$busqueda}%")
                    ->orWhereHas('lider', function ($q) use ($busqueda) {
                        $q->where('name', 'like', "%{$busqueda}%");
                    })
                    ->orWhereHas('departamento', function ($q) use ($busqueda) {
                        $q->where('name', 'like', "%{$busqueda}%");
                    });
            })
            ->orderBy('name')
            ->paginate(15);

        return view('pages.admin.teams.index', compact('equipos', 'busqueda'));
    }

    public function create(): View {
        // Verificar que el usuario tenga el rol requerido
        if (!auth()->user()->hasRole('analista-wfm')) {
            abort(403, 'Acceso denegado. Se requiere rol de Analista WFM.');
        }

        return view('pages.admin.teams.create');
    }

    public function store(CreateTeamRequest $request, CrearEquipoAction $accion): RedirectResponse {
        // Verificar que el usuario tenga el rol requerido
        if (!auth()->user()->hasRole('analista-wfm')) {
            abort(403, 'Acceso denegado. Se requiere rol de Analista WFM.');
        }

        try {
            $equipo = $accion->handle($request->validated());

            return redirect()
                ->route('admin.teams.index')
                ->with('success', 'Equipo creado correctamente.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al crear el equipo: ' . $e->getMessage());
        }
    }

    public function show(Team $team): View {
        // Verificar que el usuario tenga el rol requerido
        if (!auth()->user()->hasRole('analista-wfm')) {
            abort(403, 'Acceso denegado. Se requiere rol de Analista WFM.');
        }

        $team->load(['lider', 'departamento', 'empleados.usuario']);

        return view('pages.admin.teams.show', compact('team'));
    }

    public function edit(Team $team): View {
        // Verificar que el usuario tenga el rol requerido
        if (!auth()->user()->hasRole('analista-wfm')) {
            abort(403, 'Acceso denegado. Se requiere rol de Analista WFM.');
        }

        return view('pages.admin.teams.edit', compact('team'));
    }

    public function update(UpdateTeamRequest $request, Team $team, ActualizarEquipoAction $accion): RedirectResponse {
        // Verificar que el usuario tenga el rol requerido
        if (!auth()->user()->hasRole('analista-wfm')) {
            abort(403, 'Acceso denegado. Se requiere rol de Analista WFM.');
        }

        try {
            $accion->handle($team, $request->validated());

            return redirect()
                ->route('admin.teams.index')
                ->with('success', 'Equipo actualizado correctamente.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al actualizar el equipo: ' . $e->getMessage());
        }
    }

    public function destroy(Team $team, DesactivarEquipoAction $accion): RedirectResponse {
        // Verificar que el usuario tenga el rol requerido
        if (!auth()->user()->hasRole('analista-wfm')) {
            abort(403, 'Acceso denegado. Se requiere rol de Analista WFM.');
        }

        try {
            $accion->handle($team);

            return redirect()
                ->route('admin.teams.index')
                ->with('success', 'Equipo desactivado correctamente.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error al desactivar el equipo: ' . $e->getMessage());
        }
    }

    public function restore($id): RedirectResponse {
        // Verificar que el usuario tenga el rol requerido
        if (!auth()->user()->hasRole('analista-wfm')) {
            abort(403, 'Acceso denegado. Se requiere rol de Analista WFM.');
        }

        try {
            $equipo = Team::withTrashed()->findOrFail($id);
            $equipo->restore();

            return redirect()
                ->route('admin.teams.index')
                ->with('success', 'Equipo restaurado correctamente.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error al restaurar el equipo: ' . $e->getMessage());
        }
    }
}
