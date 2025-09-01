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
    
  public function redirect($userType = "")
{

    return Socialite::driver('google')
        ->stateless()
        ->with(['state' => $userType]) 
        ->redirect();
}

public function callback(Request $request)
{
    $googleUser = Socialite::driver('google')->stateless()->user();

    $userType = $request->state ?? 'user';

    $firstName = $googleUser->user['given_name'] ?? '';
    $lastName  = $googleUser->user['family_name'] ?? '';

    $user = User::updateOrCreate(
        ['email' => $googleUser->getEmail()],
        [
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'google_id'  => $googleUser->getId(),
            'avatar'     => $googleUser->getAvatar(),
            'user_type'  => ($userType === 'coach') ? 3 : 2,
            'user_status'=> 1,
            'email_verified' => 1,
            'is_social'  => 1,
            'is_deleted' => 0,
            'is_verified' => 1,
            'is_corporate' => 1,
            'is_active' => 1,
        ]
    );

    $token = JWTAuth::fromUser($user);

    $redirectUrl = env('FRONTEND_URL') . '/login?' . http_build_query([
        'user_type' => $userType,
        'token'     => $token,
    ]);

    return redirect()->away($redirectUrl);


}

 

}