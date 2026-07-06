<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated AND is an admin
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        }

        // If not admin, redirect to login
        abort(403);
    }
}