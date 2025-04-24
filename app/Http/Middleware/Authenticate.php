<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;

class Authenticate extends Middleware {

  protected function redirectTo(Request $request) {
    $bypassRoutes = [
      'auth.refresh', 
    ];
    if (in_array($request->route()->getName(), $bypassRoutes)) {
      return null; 
    }

    // return $request->expectsJson() ? null : route('login');
    if ($request->is('api/*')) {
      try {
        JWTAuth::parseToken()->authenticate();
      } catch (TokenExpiredException $e) {
        abort(response()->json([
          'status' => 'error',
          'key' => 'refresh-token',
          'message' => 'Token has expired.',
        ], 401));
      } catch (TokenInvalidException $e) {
        abort(response()->json([
          'status' => 'error',
          'message' => 'Token is invalid.',
        ], 401));
      } catch (JWTException $e) {
        abort(response()->json([
          'status' => 'error',
          'message' => 'Token not provided.',
        ], 401));
      }
    }

    if (! $request->expectsJson()) {
      return route('login');
    }
  }
}
