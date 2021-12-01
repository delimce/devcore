<?php

namespace App\Http\Middleware;

use App\Services\Manager\ManagerService;
use Closure;

class ApiMiddleware
{

    protected $manager;

    public function __construct(ManagerService $manager)
    {
        $this->manager = $manager;
    }

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
            if (!$this->manager->isTokenValid($token)) {
                return response()->json(['status' => 'error', 'message' => __('errors.401')], 401);
            }
        }
        return $next($request);
    }
}
