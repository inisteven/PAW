<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\ApiCode;
use App\User;
use Illuminate\Http\Request;

class VerificationController extends Controller {

    public function verify($user_id, Request $request){
        if(!$request->hasValidSignature()){
            return response(['message' => 'not valid'],404);
        }

        $user = User::findOrFail($user_id);

        if(!$user->hasVerifiedEmail()){
            $user->markEmailAsVerified();
            return response(['message' => 'email succesfuly verified'],200);
        }
        return response(['message' => 'failed'],402);

    }
}