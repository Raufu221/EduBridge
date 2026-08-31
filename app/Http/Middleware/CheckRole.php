<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        // If the user's role does NOT match the required role, block them
        if ($request->user()->role !== $role) {
            abort(403, 'Unauthorized action. You do not have permission to view this page.');
        }

        // If it matches, let them proceed
        return $next($request);
    }
}
