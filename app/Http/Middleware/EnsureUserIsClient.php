<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsClient
{
    /**
     * Only allow users with the 'client' role.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isClient()) {
            abort(403, 'Access restricted to client users.');
        }

        return $next($request);
    }
}
