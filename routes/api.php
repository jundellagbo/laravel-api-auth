<?php

use Illuminate\Http\Request;
use App\Hasher;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::bind('id', [Hasher::class, 'decode']);

Auth::routes(['verify' => true]);

Route::prefix('v1')->group(function() {
    Route::prefix('auth')->group(function() {
        // Register User
        Route::post('/register', 'v1\AuthController@register');
        // Login user
        Route::post('/login', 'v1\AuthController@login');
        // Reset Password Link
        Route::post('/request-reset-password', 'v1\AuthController@resetPassLink');
        // Show reset password form
        Route::get('/reset-password/{token}', 'v1\AuthController@showResetForm');
        // Save password with token
        Route::put('/reset-password/{token}', 'v1\AuthController@resetPassWithToken');
    });

    // Private
    Route::middleware('auth:api')->group( function() {
        // Users
        Route::prefix('user')->group(function() {
            // Get Loggedin user
            Route::get('/me', 'v1\UserController@index');
            // Resend Verification Email
            Route::get('/verify/resend', 'v1\UserController@resendVerification');
            // Verify User
            Route::get('/verify/{id}/{hash}', 'v1\UserController@verify');
        });
    });

    // Public
    Route::get('/user/{id}', 'v1\UserController@userId');
});