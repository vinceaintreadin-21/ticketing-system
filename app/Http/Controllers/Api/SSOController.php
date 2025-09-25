<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class SSOController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function callback($provider)
    {
        $socialUser = Socialite::driver($provider)->stateless()->user();

        // Check if the user already exists
        $user = User::where('provider', $provider)
                    ->where('provider_id', $socialUser->getId())
                    ->first();

        $isNewUser = false;

        if (!$user) {
            // If the user doesn't exist, create a new one
            $user = User::create([
                'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                'email' => $socialUser->getEmail(),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
                'status' => 'active',
            ]);

            $isNewUser = true;
        }

        $user->refresh();

        if($isNewUser)
        {
            if(str_ends_with($socialUser->getEmail(), '')) {

                $role = Role::where('role_name', 'mis')->first();

            } else {

                $role = Role::where('role_name', 'requester')->first();

            }

            if ($role) {
                $user->roles()->syncWithoutDetaching([$role->id]);
            }
        }

        // Generate Sanctum token for API authentication
        $token = $user->createToken('auth_token')->plainTextToken;

        // Redirect to the frontend dashboard or return a token for API authentication
        return redirect("http://localhost:5173/sso/callback?token={$token}");
;
    }

    public function dashboard()
    {
        return response()->json(['message' => 'Welcome to your dashboard!', 'user' => Auth::user()]);
    }

    public function handleLogout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
