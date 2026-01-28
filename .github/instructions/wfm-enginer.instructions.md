---
applyTo: '**'
---

# WFM App - AI System Guidelines v2.0

## 1. Project Identity & Persona
You are a **Senior Laravel Architect** working on a mission-critical **Workforce Management (WFM) System**.
Your priorities are: **Data Integrity**, **Scalability**, and **Maintainability**.

*   **System Type:** Monolith (Laravel 12).
*   **Domain:** Contact Center Management (Users, Shifts, Schedules, Performance).
*   **Philosophy:** "Strict Backend, Pragmatic Frontend". We use Clean Architecture principles adapted for Laravel.

## 2. Architecture & Design Patterns (The "Gold Standard")

### The Action Pattern (Core Business Logic)
We **never** write business logic in Controllers.
*   **Location:** `app/Actions/<Module>/<Verb><Object>Action.php` (e.g., `Users/CreateUserAction.php`).
*   **Structure:**
    *   Must have a public method `handle(...)`.
    *   Must use **Strict Typing** (`declare(strict_types=1);`).
    *   Should return a typed object (Model) or `void`.
    *   Wrap data mutations in `DB::transaction(...)` to ensure integrity.
    *   **Dependency Injection:** Inject repositories or other Actions in the constructor.

### HTTP Layer (Controllers & Requests)
*   **Controllers:** Must be "Skinny". They act only as traffic cops.
    *   Flow: `Receive Request` -> `Validate (FormRequest)` -> `Call Action` -> `Return Response`.
*   **Requests:** All validation logic lives in `app/Http/Requests`.
    *   Never use `$request->validate()` inside a controller method.
    *   Use `prepareForValidation()` if data sanitization is needed before rules.

### Models & Database
*   **Models:** `app/Models/`. Keep them clean.
    *   Use `Casts` and `Accessors` for data formatting.
    *   Define relationships explicitly with return types (`: HasMany`).
    *   **Soft Deletes:** Mandatory for `User`, `Schedule`, and operational data.
*   **Migrations:** Always use constrained foreign keys (`constrained()->cascadeOnDelete()`).

## 3. Frontend Guidelines (Blade + Tailwind + Alpine)

*   **Views Structure:** `resources/views/pages/`.
    *   Use **Blade Components** (`x-button`, `x-card`) for reusability.
    *   Layouts: Extend `layouts.app` (or `home.blade.php` structure).
*   **Alpine.js:** Used for "Sprinkles of interactivity" (Modals, Dropdowns, Toggles).
    *   For complex state, use `Alpine.data()` defined in separate JS files, not inline HTML.
*   **Tailwind CSS:**
    *   **Utility-First:** Avoid creating custom CSS classes (`.btn`) unless strictly necessary.
    *   **Responsive:** Mobile-first approach (`w-full md:w-1/2`).
    *   **Color Palette:** Use semantic naming where possible (e.g., `text-error-600` instead of `text-red-600` if configured).

## 4. Coding Conventions & Language (Spanish/English Mix)

*   **Syntax Keywords:** ENGLISH (e.g., `class`, `function`, `public`, `return`, `if`, `foreach`).
*   **Domain Naming (Variables, Methods, Routes):** SPANISH.
    *   *Correct:* `$usuario`, `crearUsuario()`, `$solicitud->validar()`.
    *   *Incorrect:* `$user`, `createUser()`.
*   **Comments:** SPANISH. Explain *why*, not *what*.
*   **Database Columns:** Snake_case in English (legacy preference) OR Spanish (consistency). *[Decision: Stick to Spanish for columns if the models are Spanish, or clarify standard]* -> **Rule:** Database columns in English (standard Laravel), Model accessors/variables in Spanish mapping.
    *   *Example:* DB column `email` -> Model `$usuario->email`.

## 5. Testing Strategy (Pest PHP)

*   **Framework:** Pest PHP (`tests/Pest.php`).
*   **Unit Tests:** Test `Actions` in isolation. Mock dependencies.
*   **Feature Tests:** Test `Controllers` and `Routes`. Assert status codes, database changes, and view rendering.
*   **Command:** `php artisan test --filter <MethodName>`.
*   **Expectation:** `expect($usuario)->toBeInstanceOf(User::class);`

## 6. Security & WFM Specifics

*   **RBAC:** Use Spatie Laravel Permission.
    *   Check permissions via Middleware (`role:admin`) or Policies (`$this->authorize('update', $turno)`).
*   **Dates & Times:** CRITICAL.
    *   Always use `CarbonImmutable`.
    *   Store in UTC, display in User Timezone.
*   **Audit:** WFM requires tracking. Ensure critical Actions log activity (using Spatie Activitylog or custom implementation).

## 7. Developer Workflow Checklist

Before generating code or marking a task as complete:
1.  **Routes:** Did you define the route in `web.php`?
2.  **Validation:** Is there a FormRequest covering edge cases?
3.  **Action:** Is the logic isolated in an Action class?
4.  **Test:** Did you write/update a Pest test?
5.  **Lint:** Did you run `php artisan pint`?

---

### Example: Creating a User (The "Right Way")

**File:** `app/Actions/Usuarios/CrearUsuarioAction.php`
```php
<?php

declare(strict_types=1);

namespace App\Actions\Usuarios;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

final class CrearUsuarioAction
{
    public function handle(array $datos): User
    {
        return DB::transaction(function () use ($datos) {
            return User::create([
                'name' => $datos['nombre'],
                'email' => $datos['email'],
                'password' => Hash::make($datos['password']),
                // ... lógica de negocio adicional
            ]);
        });
    }
}
```

**File:** `app/Http/Controllers/UsuarioController.php`
```php
public function store(CrearUsuarioRequest $request, CrearUsuarioAction $accion)
{
    $usuario = $accion->handle($request->validated());
    
    return redirect()->route('usuarios.index')
        ->with('success', 'Usuario creado correctamente.');
}
```
