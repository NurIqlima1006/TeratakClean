<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OwnerOnly
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated AND is an owner
        if (auth()->check() && auth()->user()->role === 'owner') {
            return $next($request);
        }

        // If not owner, redirect to login
        abort(403);
    }
}