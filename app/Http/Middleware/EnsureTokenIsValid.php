<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if ($token) {
            $personalAccessToken = PersonalAccessToken::findToken($token);

            if (!$personalAccessToken || ($personalAccessToken->expires_at && $personalAccessToken->expires_at->isPast())) {
                return response()->json(
                    [
                        'status' => 'error',
                        'messages' => [
                            ['type' => 'error', 'message' => 'Token is invalid or expired.']
                        ],
                        'data' => []
                    ],
                    401
                );
            }
        }

        return $next($request);
    }
}
