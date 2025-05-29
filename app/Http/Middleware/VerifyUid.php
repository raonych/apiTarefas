<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyUid
{
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->header('X-User-Id');

        if (!$userId) {
            return response()->json(['error' => 'X-User-Id não fornecido.'], 401);
        }

        return $next($request);
     
    }
}
