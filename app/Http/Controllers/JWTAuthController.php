<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Helpers\Helper;
use Symfony\Component\HttpFoundation\Response;

class JWTAuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.verify', ['except' => ['login', 'register', 'confirm']]);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validation_data = [           
            'name' => 'required|between:4,20',
            'email' => 'required|email|unique:users|max:50',
            'password' => 'required|confirmed|string|min:8',
        ];

        $response = Helper::validationCheck($request, $validation_data);
        if (!is_bool($response)) {
            return $response;
        }
        $pin = random_int(100000, 999999);
        $user = User::create(array_merge( $request->all(),
                    [
                    'password' => bcrypt($request->password),
                    'pin' => $pin
                    ]
                ));

        $to = $request->email;            
        $text = 'You have Received Signup Confirmation Pin:'.$pin;
        $subject = "Signup Confirmation!";
        $data = array('text' => $text);
        $res = Helper::sendEmail($to, $subject, $data, 'invite');
        
        if(!empty($res['error'])){                   
            $info = ['message' => $res['error']];
            return Helper::responseJson($info, $res['code']);                             
        }
         
        $info = ['message' => 'You have Recevied confirmation email. Please check you email and enter confirm pin!', ];
        return Helper::responseJson($info, Response::HTTP_OK); 
    }
    

    /**
     * confirm a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirm(Request $request)
    {
        $validation_data = [           
            'email' => 'required|email|max:50',
            'pin' => 'required|min:6|max:6',
        ];

        $response = Helper::validationCheck($request, $validation_data);
        if (!is_bool($response)) {
            return $response;
        }

        $user = User::where('email', $request->email)->first();

        if(!empty($user->email_verified_at)){            
            $info = ['message' => 'The PIN already confirm!'];
            return Helper::responseJson($info,  Response::HTTP_OK); 
        }
        
        if($user->pin == $request->pin){
            $user->pin =null;
            $user->email_verified_at = now();
            $user->registered_at = now();            
            $user->save();

            $info = ['message' => 'Users registered successfully!','data'=> $user ];
            return Helper::responseJson($info,  Response::HTTP_OK);                             
        }       
   
        $info = ['message' => 'PIN not match!'];
        return Helper::responseJson($info,  Response::HTTP_OK); 
      
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $request->only('email', 'password');

        try {

            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }

            $user = JWTAuth::setToken($token)->toUser();

            //check Active user
            if(isset($user) && (!empty($user->email_verified_at))){            
                $data = ['message' =>'LoggIn Successfully!','date'=>$user,'token' => $token];
                return Helper::responseJson($data, Response::HTTP_OK);
    
            }

            $data = ['message' => trans('messages.in_active_user')];
            return Helper::responseJson($data, Response::HTTP_BAD_REQUEST);
    

        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        $user = JWTAuth::parseToken()->authenticate();
        
        $info = ['message' => 'User profile!', 'data' => $user];
        return Helper::responseJson($info,  Response::HTTP_OK); 
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        
        JWTAuth::invalidate();

        return response()->json(['message' => 'Successfully logged out']);
    }

    

}
