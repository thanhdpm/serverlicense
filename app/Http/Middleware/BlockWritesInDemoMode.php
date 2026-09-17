<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockWritesInDemoMode
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (config('app.demo') && ! $request->isMethodSafe()) {
            abort(Response::HTTP_FORBIDDEN, 'Bạn không thể thao tác trên trang DEMO');
        }

        return $next($request);
    }
}
