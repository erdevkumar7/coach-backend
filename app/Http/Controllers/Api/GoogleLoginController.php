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
    
        public function redirect12()
    {
        // echo "test";die;
        // $showData = Socialite::driver('google')->stateless()->redirect();
        // print_r($showData);die;
        return Socialite::driver('google')->stateless()->redirect();
    }


        public function redirect(Request $request)
    {
        $userType = $request->user_type;

        return Socialite::driver('google')
            ->stateless()
            ->with(['state' => $userType]) 
            ->redirect();
    }

    public function callback78(Request $request)
{
    $googleUser = Socialite::driver('google')->stateless()->user();
    $userType = $request->state; 

    $firstName = $googleUser->user['given_name'] ?? '';
    $lastName  = $googleUser->user['family_name'] ?? '';

    if ($userType === 'coach') {
        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'first_name' => $firstName,
                'last_name'  => $lastName,
                'google_id'  => $googleUser->getId(),
                'avatar'     => $googleUser->getAvatar(),
                'user_type'  => 3,
                'user_status'=> 1,
                'email_verified' => 1,
                'is_social'  => 1,
                'is_deleted' => 0,
                'is_verified' => 1,
                'is_corporate' => 1,
                'is_active' => 1,
            ]
        );
    } else {
        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'first_name' => $firstName,
                'last_name'  => $lastName,
                'google_id'  => $googleUser->getId(),
                'avatar'     => $googleUser->getAvatar(),
                'user_type'  => 2,
                'user_status'=> 1,
                'email_verified' => 1,
                'is_social'  => 1,
                'is_deleted' => 0,
                'is_verified' => 1,
                'is_corporate' => 1,
                'is_active' => 1,
            ]
        );
    }
    
    $token = JWTAuth::fromUser($user);
    
    // Redirect back to frontend with token and user data as query parameters
    $frontendUrl = env('FRONTEND_URL', 'http://localhost:3000');
    $redirectUrl = $frontendUrl . '/login?token=' . $token . '&user=' . json_encode($user) . '&type=' . $userType;
    
    return redirect($redirectUrl);
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

    // Redirect to frontend with query params
    if($userType === 'user'){
        $redirectUrl = "https://votivereact.in/coachsparkle/user/profile?token={$token}";
    }else{
        $redirectUrl = "https://votivereact.in/coachsparkle/coach/dashboard?token={$token}";
    }

    return redirect($redirectUrl);
}

    public function callback_old(Request $request)
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        // retrieve user_type from state
        // $userType = $request->input('state', 'user'); 
        $userType = $request->state; 

        // print_r($userType);die;
        $firstName = $googleUser->user['given_name'] ?? '';
        $lastName  = $googleUser->user['family_name'] ?? '';

        if ($userType === 'coach') {
            // Save to coaches table or mark as coach
                $user = User::updateOrCreate(
                    ['email' => $googleUser->getEmail()],
                    [
                        'first_name' => $firstName,
                        'last_name'  => $lastName,
                        'google_id'  => $googleUser->getId(),
                        'avatar'     => $googleUser->getAvatar(),
                        'user_type'  => 3,
                        'user_status'=> 1,
                        'email_verified' => 1,
                        'is_social'  => 1,
                        'is_deleted' => 0,
                        'is_verified' => 1,
                        'is_corporate' => 1,
                        'is_active' => 1,
                    ]
                );
        } else {
            // Default = normal user
            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'first_name' => $firstName,
                    'last_name'  => $lastName,
                    'google_id'  => $googleUser->getId(),
                    'avatar'     => $googleUser->getAvatar(),
                    'user_type'  => 2,
                    'user_status'=> 1,
                    'email_verified' => 1,
                    'is_social'  => 1,
                    'is_deleted' => 0,
                    'is_verified' => 1,
                    'is_corporate' => 1,
                    'is_active' => 1,
                ]
            );
        }
        
        $token = JWTAuth::fromUser($user);
        return response()->json([
            'success' => true,
            'user'    => $user,
            'token'   => $token,
            'type'    => $userType,
        ]);
    }


}