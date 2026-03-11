<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsStaff
{
    /**
     * Only allow internal staff (super_admin, project_manager, team_member).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || $request->user()->isClient()) {
            abort(403, 'Access restricted to internal staff.');
        }

        return $next($request);
    }
}
