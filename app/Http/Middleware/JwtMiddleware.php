<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class JwtMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException) {
                \Log::error('Token is Invalid: ' . $e->getMessage());
                return redirect('/')->with('error', 'Your session is invalid. Please log in again.');
            } elseif ($e instanceof TokenExpiredException) {
                \Log::error('Token has Expired: ' . $e->getMessage());
                return redirect('/')->with('error', 'Your session has expired. Please log in again.');
            } else {
                \Log::error('Authorization Token not found: ' . $e->getMessage());
                return redirect('/')->with('error', 'Authorization token missing. Please log in.');
            }
        }

        return $next($request);
    }
}
