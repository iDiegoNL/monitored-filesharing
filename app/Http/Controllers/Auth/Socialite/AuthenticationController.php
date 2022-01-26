<?php

namespace App\Http\Controllers\Auth\Socialite;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class AuthenticationController extends Controller
{
    public function redirectToSteam(): RedirectResponse
    {
        return Socialite::driver('steam')->redirect();
    }

    public function handleSteamCallback(): RedirectResponse
    {
        // Get the user's Steam profile
        try {
            $steamUser = Socialite::driver('steam')->user();
        } catch (Exception) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'steam' => 'Failed to authenticate with Steam.',
                ]);
        }

        // Check if the user is TruckersMP staff
        $this->truckersMpStaffCheck($steamUser);

        // Find or create the user
        $user = User::query()
            ->firstOrCreate([
                'steam_id' => $steamUser->getId(),
            ], [
                'steam_id' => $steamUser->getId(),
                'name' => $steamUser->getName(),
                'email' => $steamUser->getEmail(),
            ]);

        // Log the user in
        Auth::loginUsingId($user->id);

        return redirect()->route('dashboard');
    }

    private function truckersMpStaffCheck(SocialiteUser $user): void
    {
        $response = Http::get("https://api.truckersmp.com/v2/player/{$user->getId()}");

        if ($response->ok() && !$response->json()['error'] && $response->json()['response']['permissions']['isStaff']) {
            return;
        }

        redirect()
            ->route('login')
            ->withErrors([
                'steam' => 'Failed to authenticate with truckersMP.',
            ]);
    }
}
