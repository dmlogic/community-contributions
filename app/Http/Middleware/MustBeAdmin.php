<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Auth\Access\AuthorizationException;

class MustBeAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if(!$request->user()->isAdmin()) {
            throw new AuthorizationException(
                'You must be an admin to do this', Response::HTTP_UNAUTHORIZED
            );
        }
        return $next($request);
    }
}
