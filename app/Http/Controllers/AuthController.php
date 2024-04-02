<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if(Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message'=>'Autorizado', 
                'token' => $request->user()->createToken('invoice', ['invoice-store', 'invoice-update'])->plainTextToken
            ]);
        }

        return response()->json(['message'=>'Nao Autorizado']);
    }
    
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json(['message' => 'Token Removido']);
    }
}
