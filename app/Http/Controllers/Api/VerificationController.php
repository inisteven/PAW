<?php

namespace App\Http\Controllers;

use App\ApiCode;
use App\User;
use Illuminate\Http\Request;

class VerificationController extends Controller {

    public function verify(Request $request) {
        // $user = User::findOrFail($request->id);

        $userID = $request['id'];
        $user = User::findOrFail($userID);
        $date = date("Y-m-d g:i:s");
        $user->email_verified_at = $date;
        $user->save();
        // do this check, only if you allow unverified user to login
//        if (! hash_equals((string) $request->id, (string) $request->user()->getKey())) {
//            throw new AuthorizationException;
//        }

        // if (! hash_equals((string) $request->hash, sha1($user->getEmailForVerification()))) {
        //     return response()->json([
        //         "message" => "Unauthorized",
        //         "success" => false
        //     ]);
        // }

        // if ($user->hasVerifiedEmail()) {
        //     return response()->json([
        //         "message" => "User already verified!",
        //         "success" => false
        //     ]);
        // }

        // if ($user->markEmailAsVerified()) {
        //     event(new Verified($user));
        // }

        return response()->json([
            "message" => "Email verified successfully!",
            "success" => true
        ]);
    }
}
