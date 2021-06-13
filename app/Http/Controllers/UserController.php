<?php

namespace App\Http\Controllers;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\User;
use Image;

class UserController extends Controller
{
    /**
     * Admin invittion send to users
     * @param Email
     * @return json response 
     */
    protected function inviteUser(Request $request){
        // Validation Check
        $validation_data = [
            'email' => 'required|email|unique:users|max:50',
        ];

        $response = Helper::validationCheck($request, $validation_data);
        if (!is_bool($response)) {
            return $response;
        }

        try{
            $to =$request->email;            
            $text = 'You have Received Signup Invition link.<a href="https://frontend.com/signup>Signup</a>';
            $subject = "Signup Invition!";
            $data = array('text' => $text);
            $res = Helper::sendEmail($to, $subject, $data, 'invite');
            
            if(!empty($res['error'])){                   
                $info = ['message' => $res['error']];
                return Helper::responseJson($info, $res['code']);                             
            }
            
            $info = ['message' => trans('messages.invition_email_send')];
            return Helper::responseJson($info, Response::HTTP_OK);
        }
        catch(\Exception $ex ){
            return Helper::internalServerError($ex);
        }
    }
    
        /**
     * Update Users Profile
     * @param $request 
     * @return json response 
     */
    protected function updateProfile(Request $request){
        // Validation Check
        $validation_data = [
            'id' => 'required',
            'name' => 'required|between:4,20',
            'avatar' => 'required|mimes:jpg,png,jpeg|max:30048',    
            'user_name' => 'required|between:4,20|unique:users,user_name,'. $request->id .'',            
        ];

        $response = Helper::validationCheck($request, $validation_data);
        if (!is_bool($response)) {
            return $response;
        }

        try{
            $data = $request->all();

            //image upload and resize the 256*256
            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                 $fileName =  time() . $avatar->getClientOriginalName();
                // $request->avatar->storeAs('avatar', $fileName, 'public');
                $destinationPath = public_path('/storage/avatar');
                $img = Image::make($avatar->path());
                $img->resize(256, 256, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$fileName);

                $data['avatar'] = $fileName;
            }
            $user = User::find($request->id)->update($data);
            
            $info = ['message' => trans('messages.prfile_update'),'data'=> $user];
            return Helper::responseJson($info, Response::HTTP_OK);
        }
        catch(\Exception $ex ){
            return Helper::internalServerError($ex);
        }
    }
}
