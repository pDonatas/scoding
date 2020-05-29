<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Cookie;
use MongoDB\Driver\Session;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        if(!Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')], true)){
            return response(['error' => 'Bad credentials'], 401);
        }

        if($update = User::where('email', $request->input('email'))->first()){
            $token = bcrypt($update->name.$update->email.time());
            $update->fill(['api_token' => $token]);
            $update->save();
        }


        return response()->json(['user' => Auth::user(), 'token' => $token],200);
    }

    public function relog($token){
        $user = User::where('api_token', $token)->first();
        if(!$user){
            return response(['error' => 'Not found'], 404);
        }

        $token = bcrypt($user->name.$user->email.time());
        $user->fill(['api_token' => $token]);
        $user->save();

        Cookie::make("jwt",$token,-1, null, null, false, false);

        return response()->json(['user' => Auth::user(), 'token' => $token],200);
    }
}
