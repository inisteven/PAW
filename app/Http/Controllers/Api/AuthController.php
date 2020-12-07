<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\VerifiesEmails;

use App\User;
use Validator;

class AuthController extends Controller
{
    
    public function register(Request $request){

        // $storeData = $request->all();
        // $validate = Validator::make($storeData, [
        //     'first_name'=>'required',
        //     'last_name'=>'required',
        //     'email' => 'required|unique',
        //     'password'=>'required',
        // ]);

        // if($validate->fails()){
        //     return response([
        //         'status'=>"fail",
        //         'message'=>"Email must be unique",
        //     ]);
        // }
        
        $registrationData = $request->all();
    
        $registrationData['password']= bcrypt($request->password); //enkripsi password
        $user = User::create($registrationData)->sendEmailVerificationNotification();
        if($user){
            return response([
                'message'=>'Register Success and send email success',
                'user'=>$user,
            ],200);    
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
        
        if(!$user->hasVerifiedEmail()){
            return response(['message' => 'Verif your email first'],402);
        }

        // $user = User::where('email', $request['email'])->where('email_verified_at', '<>', NULL)->first();
        // if (!$user) {
        //     return response([
        //         'status' => 'fail',
        //         "message" => 'Email is not verified yet',
        //         'data' => null
        //     ]);
        // }

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

    public function readData($id){
        $user = User::find($id);

        if(!is_null($user)){
            return response([
                'message'=> 'Retrieve User Success',
                'data' => $user,
            ]);
        } 

        return response([
            'message'=> 'User not found',
            'data' => null,
        ]);       
    }

    public function updatePasswordAndData(Request $request , $id){
        $user = User::find($id);

        $updateData =  $request->all();

        $validate =  Validator::make($updateData,[
            'first_name' => 'required|max:60',
            'last_name' => 'required|max:60',
            'oldPassword' => 'required',
            'newPassword' => 'required',
        ]);

        if(!Hash::check($request->oldPassword,$user->password)){
            return response([
                'status' => 'fail',
                'message' => 'Your current password is wrong',
                'data' => null
            ],400);
        }

        $user->password = bcrypt($request->newPassword);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        
        if(!$user->save()){
            return response([
                'status' => 'fail',
                'message' => 'Update data user Failed',
                'data' => null
            ],400);    
        }

        return response([
            'status' => 'success',
            'message' => 'Update data user success',
            'data' => $user
        ],200);
    }

    public function uploadImage(Request $request , $id){
        if($request->hasFile('image')){
            $user = User::find($id);
            if(is_null($user)){
                return response([
                    'message' => 'User not found',
                    'data' => null
                ],404);
            }

            $updateData = $request->all();
            $validate =  Validator::make($updateData,[
                'image' => 'mimes:jpeg,jpg,png',
            ]);

            if($validate->fails())
                return response([
                    'status'=>'fail',
                    'message' => "Image format should be JPEG, JPG, or PNG",
                ]);

            
            $file = $request->file('image');
            $ekstensi = $file->extension();
            $filename = 'IMG_'.time().'.'.$ekstensi;
            $path = base_path().'/public/profile/';
            $file->move($path,$filename);

            $user->image = $filename;

            if($user->save()){
                return response([
                    'message'=> 'Upload Image success',
                    'user'=>$user
                ]);
            }else{
                return response([
                    'message'=> 'Upload Image Fail',
                    'user'=>null
                ]);
            }
        }
    }

    public function updateUser(Request $request , $id){
        $user = User::find($id);
        if(is_null($user)){
            return response([
                'message' => 'user not found',
                'data' => null
            ],404);
        }
        $updateData =  $request->all();    
        
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        
        if(!$user->save()){
            return response([
                'status' => 'fail',
                'message' => 'Update user Failed',
                'data' => null
            ],400);    
        }

        return response([
            'status' => 'success',
            'message' => 'Update data user success',
            'data' => $user
        ],200);
    }
}
