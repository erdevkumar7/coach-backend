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
    
//   public function redirect($userType = "")
// {

//     return Socialite::driver('google')
//         ->stateless()
//         ->with(['state' => $userType]) 
//         ->redirect();
// }

// public function callback(Request $request)
// {
//     $googleUser = Socialite::driver('google')->stateless()->user();

//     $userType = $request->state ?? 'user';

//     $firstName = $googleUser->user['given_name'] ?? '';
//     $lastName  = $googleUser->user['family_name'] ?? '';

//     $user = User::updateOrCreate(
//         ['email' => $googleUser->getEmail()],
//         [
//             'first_name' => $firstName,
//             'last_name'  => $lastName,
//             'google_id'  => $googleUser->getId(),
//             'avatar'     => $googleUser->getAvatar(),
//             'user_type'  => ($userType === 'coach') ? 3 : 2,
//             'user_status'=> 1,
//             'email_verified' => 1,
//             'is_social'  => 1,
//             'is_deleted' => 0,
//             'is_verified' => 1,
//             'is_corporate' => 1,
//             'is_active' => 1,
//         ]
//     );

//     $token = JWTAuth::fromUser($user);

//     $redirectUrl = env('FRONTEND_URL') . '/login?' . http_build_query([
//         'user_type' => $userType,
//         'token'     => $token,
//     ]);

//     return redirect()->away($redirectUrl);


// }
    public function redirect(Request $request)
    {
        $userType = $request->input('user_type', 'user');
        $userType = in_array($userType, ['user', 'coach']) ? $userType : 'user';
    // dd($userType);
        $clientId = env('GOOGLE_CLIENT_ID'); 
        $redirectUri = route('google.callback'); 
        $scope = 'openid profile email'; 
        $state = $userType;

        // Build the OAuth URL
        $googleAuthUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => $scope,
            'state' => $state,
            'access_type' => 'offline', 
            'prompt' => 'consent', 
        ]);

        return response()->json([
            'redirect_url' => $googleAuthUrl
        ]);
    }




    public function callback(Request $request)
    {
        try {
        
            $googleUser = Socialite::driver('google')->stateless()->user();

        
            $userType = $request->state ?? 'user';
            $userType = in_array($userType, ['user', 'coach']) ? $userType : 'user';

            $user_type = ($userType === 'coach') ? 3 : 2;

            $existingUser = User::where('email', $googleUser->getEmail())
                ->where('is_deleted', 0)  
                ->first(); 

            if (!$existingUser) {
                $firstName = $googleUser->user['given_name'] ?? '';
                $lastName  = $googleUser->user['family_name'] ?? '';

                $newUser = User::create([
                    'first_name'      => $firstName,
                    'last_name'       => $lastName,
                    'email'           => $googleUser->getEmail(),
                    'google_id'       => $googleUser->getId(),
                    'avatar'          => $googleUser->getAvatar(),
                    'user_type'       => $user_type, 
                    'user_status'     => 1,          
                    'email_verified'  => 1,           
                    'is_social'       => 1,         
                    'last_login_at'   => now(),
                    'last_login_ip'   => $request->ip(),
                ]);

                $loginUrl = env('FRONTEND_URL') . '/login?message=' . urlencode('Registration successful. Please log in to continue.');
                return redirect()->away($loginUrl);
            }

            $firstName = $googleUser->user['given_name'] ?? '';
            $lastName  = $googleUser->user['family_name'] ?? '';

            $existingUser->update([
                'first_name'      => $firstName,
                'last_name'       => $lastName,
                'google_id'       => $googleUser->getId(),
                'avatar'          => $googleUser->getAvatar(),
                'user_status'     => 1,
                'email_verified'  => 1,
                'is_social'       => 1,
                'last_login_at'   => now(),
                'last_login_ip'   => $request->ip(),
            ]);

            $token = JWTAuth::fromUser($existingUser);

            $redirectUrl = ($existingUser->user_type === 3)
                ? env('FRONTEND_URL') . '/coach/dashboard'
                : env('FRONTEND_URL') . '/user/dashboard';
        // dd($redirectUrl,$loginUrl);
            return redirect()->away($redirectUrl);

        } catch (Exception $e) {
        dd($e->getMessage());
            return response()->json([
                'error' => 'Google authentication failed',
                'message' => $e->getMessage(),
            ], 400);
        }
    }



 

}