<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Http;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'steam_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'bool'
    ];

    public function canAccessFilament(): bool
    {
        return $this->is_admin || (str_ends_with($this->email, '@truckersmp.com') && $this->hasVerifiedEmail());
    }

    public function getTruckersMpAccount(): ?array
    {
        $response = Http::get("https://api.truckersmp.com/v2/player/{$this->steam_id}");

        if ($response->failed() || $response->json()['error']) {
            return null;
        }

        return $response->json()['response'];
    }
}
