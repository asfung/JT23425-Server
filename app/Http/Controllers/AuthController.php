<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Common\ApiCommon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;

class AuthController extends Controller {

  public function me(Request $request) {
    try {
      $user = JWTAuth::parseToken()->authenticate();
      return response()->json($user);
    } catch (TokenExpiredException $e) {
      return response()->json([
        'status' => 'error',
        'key' => 'refresh-token',
        'message' => 'Token has expired.',
      ], 401);
    } catch (TokenInvalidException $e) {
      return response()->json([
        'status' => 'error',
        'key' => 'invalid-token',
        'message' => 'Token is invalid.',
      ], 401);
    } catch (JWTException $e) {
      return response()->json([
        'status' => 'error',
        'message' => 'Token not provided.',
      ], 401);
    }
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
        'password' => 'required|string|min:8',
      ]);

      $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
      ]);

      return response()->json([
        'message' => 'User created successfully',
        'user' => $user
      ], 201);
    }catch(\Exception $e){
      return ApiCommon::sendResponse(null, $e->getMessage(), 500, false);
    }
  }

  public function logout() {
    try{
      Auth::logout();
      return response()->json([
        'message' => 'Successfully logged out',
      ]);
    } catch (TokenExpiredException $e) {
      return response()->json([
        'status' => 'error',
        'key' => 'refresh-token',
        'message' => 'Token has expired.',
      ], 401);
    } catch (TokenInvalidException $e) {
      return response()->json([
        'status' => 'error',
        'key' => 'invalid-token',
        'message' => 'Token is invalid.',
      ], 401);
    } catch (JWTException $e) {
      return response()->json([
        'status' => 'error',
        'message' => 'Token not provided.',
      ], 401);
    }
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
