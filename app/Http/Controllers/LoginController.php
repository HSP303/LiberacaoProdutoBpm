<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Domain\Senior\Services\SeniorLoginService;
use Domain\User\Services\LogoutUserService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * @return View|Factory|Application
     */
    public function create(): View|Factory|Application
    {
        return view('auth.login');
    }

    /**
     * @param LoginRequest $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        (new SeniorLoginService())->login(
            $request->validated('username'), $request->validated('password')
        );

        return redirect()->route('dashboard');
    }

    public function destroy(Request $request): Application|Redirector|RedirectResponse
    {
        (new LogoutUserService())->logout($request->user());

        return redirect()->route('login');
    }
}
