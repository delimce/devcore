<?php

namespace App\Http\Middleware;

use App\Repositories\ManagerRepository as manager;
use Closure;

class ApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('Authorization');
        if (!$token) {
            return response()->json(['status' => 'error', 'message' => __('errors.401')], 401);
        } else {
            if (!manager::isTokenValid($token)) {
                return response()->json(['status' => 'error', 'message' => __('errors.401')], 401);
            }
        }
        return $next($request);
    }
}
