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
use GuzzleHttp\Client;

class LoginController extends Controller
{
    private Client $client;

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
    public function store()
    {   
        $this->client = new Client();

        $name = 'integracaoseniorx@gramserv.com.br';
        $password = 'Integracao@2024';

        $autentication = $this->client->request('POST', 'https://platform.senior.com.br/t/senior.com.br/bridge/1.0/rest/platform/authentication/actions/login', [
            'data' => [
                [
                    'name' => $name,
                    'password' => $password,
                    'scope' => "string",
                ]
            ],
            'headers' => [
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ]
        ]);

        $autentication = json_decode($autentication->getBody(), true);
        dd($autentication);

        /*
        Auth::login(User::UpdateOrCreate([
            'name' => $request->name,
        ], [
            'name' => $request->name,
            'token' => $request->email
        ]));

        return redirect()->route('dashboard');*/
    }

    public function destroy(Request $request): Application|Redirector|RedirectResponse
    {
        (new LogoutUserService())->logout($request->user());

        return redirect()->route('login');
    }

}
