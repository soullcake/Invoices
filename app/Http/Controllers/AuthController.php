<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// 5|rUtaogjpVNoBKgI6pPX3SaRL88sJEm3sqHYF0yiC49ac353b
class AuthController extends Controller
{
    public function login(Request $request)
    {
        if(Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message'=>'Autorizado', 
                'token' => $request->user()->createToken('invoice')->plainTextToken
            ]);
        }

        return response()->json(['message'=>'Nao Autorizado']);
    }
}
