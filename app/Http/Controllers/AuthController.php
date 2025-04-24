<?php

namespace App\Http\Controllers;

use App\Common\ApiCommon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {

  public function me(Request $request) {
    return auth()->user();
  }

  public function login(Request $request) {
    try{
      $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
      ]);
      $credentials = $request->only('email', 'password');
      $email = $credentials['email'];
      $password = $credentials['password'];

      $user = User::where('email', $email)->first();
      if (!$user) {
        return response()->json([
          'status' => 'error',
          'message' => 'Email not found',
        ], 404);
      }

      if (!Hash::check($password, $user->password)) {
        return response()->json([
          'status' => 'error',
          'message' => 'Incorrect password',
        ], 401);
      }

      $token = Auth::attempt($credentials);

      if (!$token) {
        return response()->json([
          'message' => 'Unauthorized',
        ], 401);
      }

      $user = Auth::user();
      return response()->json([
        'user' => $user,
        'authorization' => [
          'token' => $token,
          'type' => 'bearer',
        ]
      ]);
    }catch(\Exception $e){
      return ApiCommon::sendResponse(null, $e->getMessage(), 500, false);
    }
  }

  public function register(Request $request) {
    try{
      $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6',
      ]);

      $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
      ]);

      return response()->json([
        'message' => 'User created successfully',
        'user' => $user
      ]);
    }catch(\Exception $e){
      return ApiCommon::sendResponse(null, $e->getMessage(), 500, false);
    }
  }

  public function logout() {
    Auth::logout();
    return response()->json([
      'message' => 'Successfully logged out',
    ]);
  }

  public function refresh()
  {
    return response()->json([
      'user' => Auth::user(),
      'authorization' => [
        'token' => Auth::refresh(),
        'type' => 'bearer',
      ]
    ]);
  }
}
