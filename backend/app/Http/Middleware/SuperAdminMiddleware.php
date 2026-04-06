<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SuperAdminMiddleware
{
    /**
     * Only allow super_admin role users
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role === 'super_admin') {
            return $next($request);
        }

        return response()->json(['error' => 'Forbidden'], 403);
    }
}
