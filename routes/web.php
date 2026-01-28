<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Ruta raíz - redirigir al dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

// Dashboard principal
Route::get('/dashboard', function () {
    return view('pages.dashboard', ['title' => 'Dashboard']);
})->name('dashboard');






















