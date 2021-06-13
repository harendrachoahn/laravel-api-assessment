<?php

namespace App\Http\Middleware;

use JWTAuth;
use Exception;
use Closure;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if($user->is_admin == 1){
                return $next($request);
            }
            
            return response()->json([
                'code' => Response::HTTP_FORBIDDEN,
                'message' => 'Only admin can access!'], Response::HTTP_FORBIDDEN);

        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json([
                    'code' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'Token is Invalid'], Response::HTTP_UNAUTHORIZED);

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){                
                return response()->json([
                    'code' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'Token is Expired'], Response::HTTP_UNAUTHORIZED);
            }else{                
                return response()->json([
                    'code' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'Authorization Token not found'], Response::HTTP_UNAUTHORIZED);
               
            }
        }   

    }
}
