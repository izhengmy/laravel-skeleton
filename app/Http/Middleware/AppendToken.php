<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AppendToken
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$routeNames
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request, Closure $next, ...$routeNames)
    {
        $response = $next($request);

        if (! in_array($request->route()->getName(), $routeNames)) {
            return $response;
        }

        if (empty($token = $request->input('token'))) {
            return $response;
        }

        $payload = JWTAuth::setToken($token)->getPayload();
        $exp = $payload->get('exp');
        $minutes = floor(($exp - time()) / 60);

        $response = $next($request);
        $response->withCookie(cookie('token', $token, $minutes));

        return $response;
    }
}
