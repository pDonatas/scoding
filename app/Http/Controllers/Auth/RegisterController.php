<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function register(Request $request){
        $user = User::where('email', $request->email)->first();

        if(isset($user->id)){
            return response()->json(['error' => 'Email already exists'], 401);
        }

        $user = new User();

        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->email = $request->email;
        $user->save();

        return response()->json('', 200);
    }
}
