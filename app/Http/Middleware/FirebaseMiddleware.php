<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Symfony\Component\HttpFoundation\Response;

class FirebaseMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        //remove o token da header da request 
        $idToken = $request->bearerToken();

        //se não estiver definido um token retorna 401 por padrão
        if (!$idToken) {
            return response()->json(['error' => 'Token não fornecido.'], 401);
        }

        try {
            $auth = (new Factory)
                ->withServiceAccount(storage_path(env('FIREBASE_CREDENTIALS')))
                ->createAuth();

            $verifiedIdToken = $auth->verifyIdToken($idToken);
            $userId = $verifiedIdToken->claims()->get('sub');

            // Anexa o userId ao body
            $request->merge(['userId' => $userId]);

            return $next($request);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Token inválido ou expirado.',
                'message' => $e->getMessage()
            ], 401);
        }
    }
}
