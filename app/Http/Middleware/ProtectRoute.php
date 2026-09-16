<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ProtectRoute
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

        if (!config('app.demo', env('APP_DEMO', false))) {
            return $next($request);
        } else {
            return response('Bạn không thể thao tác trên trang DEMO');
        }
    }
}
