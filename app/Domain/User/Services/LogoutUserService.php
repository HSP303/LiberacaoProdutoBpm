<?php

namespace Domain\User\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

readonly class LogoutUserService
{
    public function logout(?User $user = null): void
    {
        if (!$user) {
            $user = Auth::user();
        }

        $user->update([
            'user' => null,
            'password' => null,
        ]);

        $user->productionOrders()->delete();

        Request::session()->invalidate();
        Request::session()->regenerateToken();

        Auth::guard('web')->logout();
    }
}
