<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        $query = http_build_query([
            'client_id' => config('services.google.client_id'),
            'redirect_uri' => config('services.google.redirect'),
            'response_type' => 'code',
            'scope' => 'openid profile email',
        ]);

        return redirect('https://accounts.google.com/o/oauth2/auth?' . $query);
    }

    public function handleGoogleCallback(Request $request)
    {
        // 1. Intercambio del código de autorización por un token de acceso
        $response = Http::post('https://oauth2.googleapis.com/token', [
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'code' => $request->code,
            'redirect_uri' => config('services.google.redirect'),
            'grant_type' => 'authorization_code',
        ]);

        $accessToken = $response->json()['access_token'];

        // 2. Obtención de la información del usuario
        $googleUserResponse = Http::withToken($accessToken)
                                  ->get('https://www.googleapis.com/oauth2/v3/userinfo');

        $googleUser = $googleUserResponse->json();
        
        // 3. Autenticación o creación del usuario en la base de datos
        $user = User::firstOrCreate(
            ['email' => $googleUser['email']],
            [
                'name' => $googleUser['name'],
                'password' => bcrypt(Str::random(16)), // Generar una contraseña aleatoria
            ]
        );

        // 4. Iniciar sesión y redirigir
        Auth::login($user);

        return redirect('/dashboard'); // Redirige a la página de inicio de tu aplicación
    }
}