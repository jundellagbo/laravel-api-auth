<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;
use App\UserToken;


use Illuminate\Support\Str;
/**
 * @group User Endpoint
 * 
 */

class UserController extends Controller
{
    //
    public $token;

    public function __construct() {
        $this->token = new UserToken();
    }

    /**
     * 
     * Authorized User Account
     * 
     * Headers: Bearer Token
     * 
     */

     public function index(Request $request) {
        return response()->json(['data' => $request->user()], 200);
     }

     /**
      * Get User By ID
      * 
      * @queryParam id required
      */

    public function userId(Request $request) {
        $user = User::find($request->id);
        if($user) {
            return response()->json(['data' => User::find($request->id)], 200);
        }
        return response()->json(['message' => 'User not found.'], 404);
    }
    
    /**
     * 
     * Verify User Account
     * 
     * Headers: Bearer Token
     * 
     * @queryParams token required
     * 
     */
      public function verify(Request $request, $token = null) {
        if($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'User already verified.'], 422);
        }
        $token = $this->token->getVerifyToken($token);
        if(!$token) return response()->json(['message' => 'Verification token is invalid.'], 422);
        $request->user()->markEmailAsVerified();
        $this->token->delete($token);
        return response()->json(['message' => 'User has been verified'], 200);
    }

    /**
     * 
     * Resend Verification Email
     * 
     * Headers: Bearer Token
     * 
     */
    public function resendVerification(Request $request) {
        if($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'User already verified.'], 422);
        }
        $request->user()->sendEmailVerificationNotification();
        return response()->json(['message' => 'Please check your email for verification.'], 200);
    }
}