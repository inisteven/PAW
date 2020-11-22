<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Validator;

class AuthController extends Controller
{
    public function register(Request $request){

        $registrationData = $request->all();
        $validate = Validator::make($registrationData,[
            'first_name' => 'required|max:60', 
            'last_name' => 'required|max:60',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required',
        ]);//membuat rule validasi input

        if($validate->fails())
            return response(['message'=> $validate->errors()],400); //return error invalid input

            $registrationData['password']= bcrypt($request->password); //enkripsi password
            $user = User::create($registrationData)->sendEmailVerificationNotification();//membuat user baru
            // $accessToken = $user->createToken('authToken')->access_token;
            return response([
                'message'=>'Register Success',
                'user'=>$user,
                // 'access_token'=>$accessToken
            ],200);//return data user dalam bentuk json
    }

    public function login(Request $request){
        $user = User::where('email', $request['email'])->where('email_verified_at', '<>', NULL)->first();

        if (!$user) {
            return [
                "response" => 'Email is not verified'
            ];
        }
        
        $loginData = $request->all();
        $validate = Validator::make($loginData,[
            'email' => 'required|email:rfc,dns',
            'password' => 'required',
        ]);//membuat rule validasi input

        if($validate->fails())
            return response(['message'=> $validate->errors()],400); //return error invalid input

        if(!Auth::attempt($loginData))
            return response(['message' => 'Invalid Credentials'],401); //return error gagal login

        $user = Auth::user();
        $token = $user->createToken('Authentication Token')->accessToken; //generate token

        return response([
            'message'=>'Authenticated',
            'user'=>$user,
            'token_type'=>'Bearer',
            'access_token'=>$token,
        ],200);//return data user dalam bentuk json
    }
}
