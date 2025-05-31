<?php

namespace App\Http\Middleware;

use Closure;

class EventMiddleware
{
    public function handle($request, Closure $next)
    {
        // If the request method is PUT or PATCH, try to get the id_event from the request data
        if ($request->isMethod('put') || $request->isMethod('patch')) {
            $id_akumulasi = $request->input('id_akumulasi');
        } else {
            // Otherwise, try to get the id_event from the route parameters
            $id_akumulasi = $request->route('id_akumulasi');
        }

        // Share the id_event to all views
        view()->share('id_akumulasi', $id_akumulasi);

        return $next($request);
    }
}
