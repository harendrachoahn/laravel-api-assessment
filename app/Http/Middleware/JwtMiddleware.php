<?php
namespace App\Http\Middleware;

use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Closure;
use JWTAuth;    

class JwtMiddleware extends BaseMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        try {  
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json([
                    'code' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'User not found by given token'
                ], Response::HTTP_UNAUTHORIZED);
            } 

        } catch (TokenInvalidException $e) {
            return response()->json([
                'code' => Response::HTTP_UNAUTHORIZED,
                'message' => $e->getMessage()
            ], Response::HTTP_UNAUTHORIZED);
        } catch (TokenExpiredException $e) {

            return response()->json([
                'code' => Response::HTTP_UNAUTHORIZED,
                'message' => "Session has Expired"
            ], Response::HTTP_UNAUTHORIZED);
        } catch (JWTException $e) {

            return response()->json([
                'code' => Response::HTTP_UNAUTHORIZED,
                'message' => $e->getMessage()
            ], Response::HTTP_UNAUTHORIZED);
        }
        
        return $next($request);
    }
}