<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class CheckAdmin
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
        //return response(['error' => $request->token], 403);
        if($request->token != '' && $request->token != NULL){
            $user = User::where('api_token', $request->token)->first();
            if($user == NULL || $user->type != 1){
                return response(['error' => '403 Permission Denied'], 403);
            }
        }else{
            return response(['error' => '403 Permission Denied'], 403);
            //return response(['error' => $request], 403);
        }

        return $next($request);
    }
}
