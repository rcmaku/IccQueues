<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckManagerOrAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is not authenticated or doesn't have the required roles
        if (!Auth::check() || (!Auth::user()->hasRole('manager') && !Auth::user()->hasRole('admin'))) {
            // If the user is already trying to access the roles page, don't redirect again
            if ($request->is('roles*')) {
                return redirect()->route('roles.index'); // Redirect to a safe page (like home)
            }

            // Redirect to the roles index page if the user doesn't have permission
            return redirect()->route('roles.index')->with('error', 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
