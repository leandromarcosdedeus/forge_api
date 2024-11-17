<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/register', function (Request $request){

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

});

Route::post('/login', function (Request $request) {
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
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

