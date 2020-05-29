<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeController extends Controller
{

    public function __invoke(Request $request){
        if($request->get('token') != '') {
            $user = User::where('api_token', $request->token)->first();
            return response()->json(['user' => $user], 200);
        }
    }
}
