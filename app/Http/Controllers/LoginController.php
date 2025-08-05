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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;

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
    public function store(Request $request)
    {
        $token = $request->cookie();
        dd($token);
        /*
        $request->validate([
            'name' => 'required',
            'email' => 'required',
        ]);

        Auth::login(User::UpdateOrCreate([
            'name' => $request->name,
        ], [
            'name' => $request->name,
            'email' => $request->email
        ]));

        return redirect()->route('dashboard');*/
    }

    public function destroy(Request $request): Application|Redirector|RedirectResponse
    {
        (new LogoutUserService())->logout($request->user());

        return redirect()->route('login');
    }

}
