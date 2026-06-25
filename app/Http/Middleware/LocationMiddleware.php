<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LocationMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->hasSession()) {
            $location = $request->session()->get('current_location', 'Gurgaon');
            app()->instance('current_location', $location);
        }

        return $next($request);
    }
}
