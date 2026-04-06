<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OrgScopingMiddleware
{
    /**
     * Apply org_id scoping to queries
     * Super admin bypasses this
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role !== 'super_admin') {
            // Set org_id context for query scoping
            app()->instance('org_id', auth()->user()->org_id);
        }

        return $next($request);
    }
}
