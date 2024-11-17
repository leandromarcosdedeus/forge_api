<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if(Auth::attempt($credentials))
        {
            $user = $request->user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'acess_token' => $token,
                'token_type' => 'Bearer'
            ]);
        }

        return response()->json([
            "message" => 'Usuário inválido!'
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|unique:users',
            'name' => 'required|string',
            'password' => 'required|string|min:8'
        ]);

        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => Hash::make($request->password)
        ]);


        return response()->json([
            'message' => 'Sucesso',
            'user' => $user
        ]);
    }
}
