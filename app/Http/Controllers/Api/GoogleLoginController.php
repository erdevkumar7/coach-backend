<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class GoogleLoginController extends Controller
{
    
        public function redirect()
    {
        // echo "test";die;
        // $showData = Socialite::driver('google')->stateless()->redirect();
        // print_r($showData);die;
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function callback()
{
    $googleUser = Socialite::driver('google')->stateless()->user();

    // Extract first & last name safely
    $firstName = $googleUser->user['given_name'] ?? '';
    $lastName  = $googleUser->user['family_name'] ?? '';

    // Find or create user
    $user = User::updateOrCreate(
        ['email' => $googleUser->getEmail()],
        [
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'google_id'  => $googleUser->getId(),
            'avatar'     => $googleUser->getAvatar(),
            'user_status'=> 1,
            'email_verified' => 1,
            'is_social'  => 1,
            'is_deleted' => 0,
        ]
    );

    // Generate JWT token
    $token = JWTAuth::fromUser($user);

    return response()->json([
        'success' => true,
        'user'    => $user,
        'token'   => $token,
    ]);
}

}