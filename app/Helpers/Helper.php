<?php
 namespace App\Helpers;

 use JWTAuth;
 use Symfony\Component\HttpFoundation\Response;
 use Illuminate\Support\Facades\Validator;
 use Illuminate\Support\Facades\Mail;
 use Exception;
 
 class Helper
 {

    public function __construct()
    {

    }

    /*
    *responseJson for send respone with response code
    * 
    * */
      public static function responseJson($additionalData, $response_code)
        {
            $responseData =  ['code' => $response_code];
            return response()->json(array_merge($responseData, $additionalData),$response_code);
        }

    /*
    *Check is Valid Email or not
    * 
    * */
    public static function isValidEmail($email){

      return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;

    }

    /*
    *Check is Valid Email or not
    * */
    public static function isVaildMobile($mobile)
    {
        return preg_match('/^[0-9]{10}+$/', $mobile);
    }

    /*
    *Common function for cahch block handling internal Server Errors
    * */
    public static function internalServerError($ex)
    {
        return response()->json([
            'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'message' => 'something went wrong server side!',
            'error' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
       // @file_put_contents($file_name,PHP_EOL .date("Y-m-d H:i:s", strtotime("now"))."==== Mobile Number ===". $mobile. " === Error message ===".print_r($ex,true).PHP_EOL,FILE_APPEND);
    }
    /*
    *Common function for Send Email
    *@param 
    * */
    public static function sendEmail($to, $subject, $data, $view)
    {
      try{        
      Mail::send('emails.'.$view,  $data , function($message) use ($to,$subject)
        {    
            $message->to($to)->subject($subject);    
        });
        return array('error' => null);   

      }catch( Exception $ex){
        return array('error' => $ex->getMessage(),'code' => 500);
      }

    }

    /**
     * get User Id form JWT token
     */
    public static function getUserId()
    {
        $user_data = JWTAuth::parseToken()->authenticate();
        return $user_data->id;
    }

    /**
     * get User form JWT token
     */

    public static function getUser()
    {
        $user_data = JWTAuth::parseToken()->authenticate();
        return $user_data;
    }

    /**
     * Common image upload Function
     */
    public static function imageUpload($request, $storage)
    {   
        // check if image has been received from form
        if($request->file('image')){
          ini_set('memory_limit', '24M');

          // processing the uploaded image
          $avatar_name =  time().'.'.$request->file('image')->getClientOriginalExtension();
          $avatar_path = $request->file('image')->storeAs('',$avatar_name, $storage);

          return $avatar_path;
        }
        return false;
              
    }

    
    /**
     * common function for validation check
     */
    public static function validationCheck($request,$data)
    {
        $validation = Validator::make($request->all(), $data);
        if ($validation->fails()) {           
            $additionalData = ['message' => $validation->messages()->first()];
            return Helper::responseJson($additionalData, Response::HTTP_BAD_REQUEST);
        }
        return true;
    }





}
