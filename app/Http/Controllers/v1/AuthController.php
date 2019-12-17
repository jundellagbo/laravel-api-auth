<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\UserToken;

/**
 * @group Authentication Endpoint
 * 
 */

class AuthController extends Controller
{

    public $token;

    public function __construct() {
        $this->token = new UserToken();
    }
    
    public $validations = [
        'firstName' => 'required|string|max:100',
        'lastName' => 'required|string|max:100',
        'email' => 'required|string|max:100|unique:users',
        'gender' => 'string|max:6',
        'birthDate' => 'string',
        'watsappNumber' => 'string|max:15',
        'phoneNumber' => 'string|max:15',
        'passportNumber' => 'string|max:25',
        'visaNumber'  => 'string|max:25',
        'accessCardNumber'  => 'string|max:25',
        'password' => 'string|max:20|min:5|required'
    ];

    //
    /**
     * 
     * Register User
     * 
     * User registration API endpoint
     * 
     * @bodyParam firstName string required max:100
     * @bodyParam lastName string required max:100
     * @bodyParam email string required max:100,unique
     * @bodyParam password string required max:20,min:5,unique,confirmed:password_confirmation
     * @bodyParam gender string max:6
     * @bodyParam birthDate string MM-DD-YYYY
     * @bodyParam watsappNumber string max:15
     * @bodyParam phoneNumber string max:15
     * @bodyParam passportNumber string max:25
     * @bodyParam visaNumber string max:25
     * @bodyParam accessCardNumber string max:25
     * 
     */
    public function register(Request $request) {
        $this->validations['password'] = $this->validations['password'].'|confirmed';
        $validator = Validator::make($request->all(), $this->validations);
        if($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 422);
        }
        $request['password'] = Hash::make($request['password']);
        $user = User::create($request->toArray());
        $token = $user->createToken(env('PASSPORT_TOKEN'))->accessToken;
        $this->token->create(['email' => $request->email, 'type' => 'verify'])->sendEmail();
        return response()->json(['access_token' => $token, 'user' => $user], 200);
    }

    /**
     * Login User
     * 
     * @bodyParam email string required max:100
     * @bodyParam password string required max:20,min:5
     */
    public function login(Request $request) {
        $credentials = $request->only('email', 'password');
        if(auth()->attempt($credentials)) {
            return response()->json(['data' => auth()->user(), 'access_token' => auth()->user()->createToken(env('PASSPORT_TOKEN'))->accessToken], 200);
        }
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    /**
     * 
     * Request Reset Password
     * 
     * Send reset password link via email
     * 
     * @bodyParam email string required
     * @bodyParam recaptcha_token string required
     * 
     */

     public function resetPassLink(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:100|exists:users,email',
            //'recaptcha_token' => ['required', new ReCaptcha]
        ]);

        if ($validator->fails())
        {
            return response()->json(['message' => $validator->errors()], 422);
        }

        $this->token->create(['email' => $request->email, 'type' => 'password_reset'])->sendEmail();
        return response()->json(['message' => 'sent'], 200);
     }


     /**
       * 
       * Generate Reset Password Link
       * 
       * @queryParam token required
       * 
       */
      public function showResetForm($token = null) {
        $token = $this->token->getPasswordToken($token);
        if(!$token) return response()->json(['message' => 'Token has been expired'], 422);
        return response()->json(['data' => $token], 200);
    }

    /**
     * 
     * Save Password with Token
     * 
     * @bodyParam password
     * @bodyParam password_confirmation
     * 
     */
    public function resetPassWithToken(Request $request, $token = null) {
      $req = array_merge($request->all(), ['token' => $token]);
      $validator = Validator::make($req, [
          'password' => 'string|max:20|min:5|required|confirmed',
          'token' => 'required'
      ]);
      if ($validator->fails())
      {
          return response()->json(['message' => $validator->errors()], 422);
      }
      $tokenize = $this->token->getPasswordToken($token);
      if(!$tokenize) return response()->json(['message' => 'Token has been expired.'], 422); 
      $user = User::where('email', $tokenize->email)->first();
      $user->password = Hash::make($request->password);
      $user->remember_token = str_replace('-', '', (string) Str::uuid());
      $user->save();
      $this->token->delete($token);
      return response()->json(['message' => 'saved'], 200);
    }
}