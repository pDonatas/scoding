<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class Auth
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
        $user = User::where('api_token', $request->token)->first();
        if($user != NULL){
            return $next($request);
        }
        return response(['error' => '403 Permission Denied'], 403);
    }
}
