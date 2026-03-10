<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GoogleAuthController;
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('login.google');

// Ruta de callback para manejar la respuesta de Google
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);
/*Route::get('login2', function () {
    Auth::loginUsingId(1);
    return redirect('/home');
})->name('login2');*/
Route::get('login', function () {
   
    return view('auth.login');
})->name('login');

Route::middleware('auth')->group(function () {
Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', function () {
    return view('welcome');
})->middleware('auth')->name('home');

// Fallback para rutas de la SPA (Vue). Devuelve la vista principal para que Vue Router maneje la ruta.
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
});