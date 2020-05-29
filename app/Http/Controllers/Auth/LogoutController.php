<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function __invoke(Request $request)
    {
        $token = $request->get('token');
        if($token != '') {
            $user = User::where('api_token', $token)->first();
            if ($user != null) {
                $user->fill(['api_token' => null]);
                $user->save();
            }else{
                return response(["error" => $token], 401);
            }
        }else{
            return response(["error" => "Unauthorized"], 401);
        }
    }
}
