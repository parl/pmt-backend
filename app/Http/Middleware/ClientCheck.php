<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ClientCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->role == "client" || $request->user()->role == "admin") {
            return $next($request);
        }
        return response()->json([
            "apiData" => null,
            "message" => "Not Authenticated",
            "status" => "Forbidden"
        ], 403);
    }
}
