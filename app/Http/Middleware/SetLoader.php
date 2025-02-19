<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLoader
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the request is for a data retrieval route
        if ($request->isMethod('GET') && $request->segment(1) !== 'api') {
            // Set a loader variable
            $request->loader = true;
        }

        return $next($request);
    }
}