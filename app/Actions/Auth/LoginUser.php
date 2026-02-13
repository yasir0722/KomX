<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginUser
{
    /**
     * Attempt to authenticate a user with email and password.
     */
    public function execute(string $email, string $password): ?User
    {
        if (! Auth::attempt(['email' => $email, 'password' => $password])) {
            return null;
        }

        return User::where('email', $email)->first();
    }
}
