<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ConfiguracionController extends Controller {
   /**
    * Mostrar la página de configuración del sistema
    */
   public function index() {
      // Verificar que el usuario tenga el rol analista-wfm
      if (!auth()->user()->hasRole('analista-wfm')) {
         abort(403, 'No tienes permisos para acceder a esta sección.');
      }

      // Obtener configuraciones del sistema (esto podría venir de una tabla de configuración)
      $configuraciones = [
         'app_name' => config('app.name', 'WFM Reporter'),
         'app_timezone' => config('app.timezone', 'UTC'),
         'mail_driver' => config('mail.default', 'smtp'),
         'cache_driver' => config('cache.default', 'file'),
         'session_driver' => config('session.driver', 'file'),
         'database_connection' => config('database.default', 'pgsql'),
      ];

      return view('pages.admin.configuracion.index', compact('configuraciones'));
   }

   /**
    * Actualizar configuraciones del sistema
    */
   public function update(Request $request) {
      // Verificar que el usuario tenga el rol analista-wfm
      if (!auth()->user()->hasRole('analista-wfm')) {
         abort(403, 'No tienes permisos para acceder a esta sección.');
      }

      $request->validate([
         'app_name' => 'required|string|max:255',
         'app_timezone' => 'required|string|timezone',
         'mail_driver' => 'required|string|in:smtp,mailgun,ses,postmark,sendmail',
         'cache_driver' => 'required|string|in:file,database,redis,memcached',
         'session_driver' => 'required|string|in:file,cookie,database,redis,memcached',
         'database_connection' => 'required|string|in:pgsql,mysql,sqlite,sqlsrv',
      ]);

      try {
         DB::transaction(function () use ($request) {
            // Aquí normalmente guardaríamos en una tabla de configuración
            // Por ahora, solo validamos y mostramos mensaje de éxito
            // En un sistema real, esto actualizaría archivos .env o una tabla de configuración

            $configuraciones = [
               'app_name' => $request->app_name,
               'app_timezone' => $request->app_timezone,
               'mail_driver' => $request->mail_driver,
               'cache_driver' => $request->cache_driver,
               'session_driver' => $request->session_driver,
               'database_connection' => $request->database_connection,
            ];

            // Guardar en cache temporal (en producción esto iría a BD o archivos)
            Cache::put('system_config', $configuraciones, now()->addHours(24));
         });

         return redirect()->route('admin.configuracion.index')
            ->with('success', 'Configuración actualizada correctamente.');

      } catch (\Exception $e) {
         return redirect()->back()
            ->withInput()
            ->with('error', 'Error al actualizar la configuración: ' . $e->getMessage());
      }
   }

   /**
    * Limpiar cachés del sistema
    */
   public function clearCache() {
      // Verificar que el usuario tenga el rol analista-wfm
      if (!auth()->user()->hasRole('analista-wfm')) {
         abort(403, 'No tienes permisos para acceder a esta función.');
      }

      try {
         // Limpiar diferentes tipos de caché
         Cache::flush();
         \Artisan::call('config:clear');
         \Artisan::call('route:clear');
         \Artisan::call('view:clear');

         return redirect()->route('admin.configuracion.index')
            ->with('success', 'Cachés del sistema limpiados correctamente.');

      } catch (\Exception $e) {
         return redirect()->route('admin.configuracion.index')
            ->with('error', 'Error al limpiar cachés: ' . $e->getMessage());
      }
   }

   /**
    * Ejecutar comandos de mantenimiento
    */
   public function maintenance(Request $request) {
      // Verificar que el usuario tenga el rol analista-wfm
      if (!auth()->user()->hasRole('analista-wfm')) {
         abort(403, 'No tienes permisos para acceder a esta función.');
      }

      $request->validate([
         'command' => 'required|string|in:optimize,clear-compiled,key-generate,migrate-status',
      ]);

      try {
         $output = '';

         switch ($request->command) {
            case 'optimize':
               \Artisan::call('optimize');
               $output = 'Aplicación optimizada correctamente.';
               break;
            case 'clear-compiled':
               \Artisan::call('clear-compiled');
               $output = 'Archivos compilados limpiados correctamente.';
               break;
            case 'key-generate':
               \Artisan::call('key:generate');
               $output = 'Clave de aplicación generada correctamente.';
               break;
            case 'migrate-status':
               \Artisan::call('migrate:status');
               $output = 'Estado de migraciones verificado.';
               break;
         }

         return redirect()->route('admin.configuracion.index')
            ->with('success', $output);

      } catch (\Exception $e) {
         return redirect()->route('admin.configuracion.index')
            ->with('error', 'Error al ejecutar comando: ' . $e->getMessage());
      }
   }
}
