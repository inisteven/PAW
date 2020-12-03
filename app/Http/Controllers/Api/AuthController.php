<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\VerifiesEmails;

use App\User;
use Validator;

class AuthController extends Controller
{
    use VerifiesEmails;
    public function register(Request $request){

        $validate = Validator::make($storeData, [
            'email' => 'required|unique'
        ]);

        if($validate->fails()){
            return response([
                'status'=>"fail",
                'message'=>"Email must be unique",
            ]);
        }
        
        $registrationData = $request->all();
    
        $registrationData['password']= bcrypt($request->password); //enkripsi password
        $user = User::create($registrationData);
        if($user){
            $send = $user->sendEmailVerificationNotification();
            if($send){
                return response([
                    'message'=>'Register Success and send email success',
                    'user'=>$user,
                ],200);
            }else{
                return response([
                    'message'=>'Register success and send email failed',
                    'user'=>$user,
                ],200);
            }
        }
        return response([
            'message'=>'Register failed',
            'user'=>null,
        ]); 
    }

    public function login(Request $request){

        $email = $request->email;
        $user = User::where('email','=',$email)->first();
        
        if(is_null($user)){
            return response([
                'status' => 'fail',
                'message' => "Email is not registered yet",
                'data' => null
            ]);
        }
        
        $loginData = $request->all();
        if(!Auth::attempt($loginData))
            return response([
                'status' => 'fail',
                'message' => 'Invalid Credentials',
                'data' => null,
            ],401); 

        $user = User::where('email', $request['email'])->where('email_verified_at', '<>', NULL)->first();
        if (!$user) {
            return response([
                'status' => 'fail',
                "message" => 'Email is not verified yet',
                'data' => null
            ]);
        }

        $user = Auth::user();
        // $user = $this->auth->user();
        $token = $user->createToken('Authentication Token')->accessToken; //generate token

        return response([
            'status' => 'success',
            'message'=>'Sign in successfull',
            'user'=>$user,
            'token_type'=>'Bearer',
            'access_token'=>$token,
        ],200);//return data user dalam bentuk json
    }
}
