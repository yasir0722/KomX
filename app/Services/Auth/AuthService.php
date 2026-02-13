<?php

namespace App\Services\Auth;

use App\Repositories\Auth\AuthRepository;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function __construct(
        private readonly AuthRepository $authRepository,
    ) {}

    /**
     * Attempt to authenticate a user.
     */
    public function login(string $email, string $password): ?User
    {
        if (! Auth::attempt(['email' => $email, 'password' => $password])) {
            return null;
        }

        return $this->authRepository->findByEmail($email);
    }

    /**
     * Log the user out and invalidate the session.
     */
    public function logout(Request $request): void
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
