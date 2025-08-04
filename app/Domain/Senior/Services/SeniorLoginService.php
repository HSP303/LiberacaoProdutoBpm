<?php

namespace Domain\Senior\Services;

use App\Models\User;
use Domain\Integrations\ExecuteSeniorSoap;
use Domain\Integrations\SeniorSoapClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;

readonly class SeniorLoginService
{
    /**
     * @param string $user
     * @param string $password
     * @throws ValidationException
     */
    public function login(string $user, string $password): void
    {
        $soap = new SeniorSoapClient('sapiens_SyncMCWFUsers?wsdl');

        $response = (new ExecuteSeniorSoap)->execute($soap, 'authenticateJAAS', [
            null, null, 0, [
                "pmUserName" => $user,
                "pmUserPassword" => $password,
                'pmEncrypted' => 0
            ]
        ]);

        if ($response['pmLogged'] == -1) {
            throw ValidationException::withMessages([
                'username' => __('auth.failed'),
            ]);
        }

        Auth::login(User::UpdateOrCreate([
            'name' => $user,
        ], [
            'name' => $user,
            'user' => Crypt::encrypt($user),
            'password' => Crypt::encrypt($password),
        ]));

    }
}
