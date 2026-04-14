<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */

    public function handle($request, Closure $next, $role)
    {
    if (!Auth::check()) {
        return redirect('/login');
    }

    if (Auth::user()->role != $role) {
        abort(403);
    }

    return $next($request);
    }

    
}


