<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $domains = ['http://localhost:8080','https://ensalamentoapp.herokuapp.com'];

        if (isset($request->server()['HTTP_ORIGIN'])) {
            $origin = $request->server()['HTTP_ORIGIN'];
            // if (in_array($origin, $domains)) {
            if(true) {
                header('Access-Control-Allow-Origin: ' . $origin);
                header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization');
                header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
            }
        }
        return $next($request);
    }
}
