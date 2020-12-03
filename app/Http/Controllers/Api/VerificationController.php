<?php

namespace App\Http\Controllers;

use App\ApiCode;
use App\User;
use Illuminate\Http\Request;

class VerificationController extends Controller {

    public function __construct() {
        $this->middleware('auth:api')->except(['verify']);
    }
    
    public function verify($user_id, Request $request) {
        if (! $request->hasValidSignature()) {
            return $this->respondUnAuthorizedRequest(ApiCode::INVALID_EMAIL_VERIFICATION_URL);
        }

        $user = User::findOrFail($user_id);

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return redirect()->to('/');
    }
}