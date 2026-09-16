<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class App
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
        $userInput = $request->all();
        array_walk_recursive($userInput, function (&$userInput) {
            $userInput = strip_tags($userInput);
        });
        $request->merge($userInput);

        if (app()->environment('testing')) {
            return $next($request);
        }

        $defaultConn = config('database.default', 'mysql');
        $dbDatabase = config("database.connections.{$defaultConn}.database") ?: env('DB_DATABASE');
        $dbUsername = config("database.connections.{$defaultConn}.username") ?: env('DB_USERNAME');

        if ($defaultConn === 'sqlite') {
            if (empty($dbDatabase)) {
                return response(view('setup'));
            }
        } else {
            if (empty($dbDatabase) || empty($dbUsername)) {
                return response(view('setup'));
            }
        }
        
        return $next($request);
    }
}
