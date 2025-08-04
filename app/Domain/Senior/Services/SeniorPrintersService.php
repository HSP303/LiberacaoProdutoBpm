<?php

namespace Domain\Senior\Services;

use App\Helpers\AppHelper;
use App\Models\User;
use Domain\Integrations\ExecuteSeniorSoap;
use Domain\Integrations\SeniorSoapClient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;

readonly class SeniorPrintersService
{

    /**
     * @return array[]
     */
    public function list(User $user): array
    {
        $soap = new SeniorSoapClient('sapiens_Synccom_Soeltech_Impressoras?wsdl');

        return Cache::remember(
            'senior_printers',
            null,
            fn() => $this->listSeniorPrinters($soap, $user)
        );
    }

    /**
     * @param SeniorSoapClient $soap
     * @param User $user
     * @return array|array[]
     * @throws ValidationException
     */
    private function listSeniorPrinters(SeniorSoapClient $soap, User $user): array
    {
        $response = (new ExecuteSeniorSoap())->execute($soap,
            'Listar',
            [
                Crypt::decrypt($user->getAttribute('user')),
                Crypt::decrypt($user->getAttribute('password')),
                0,
                [],
            ],
        );

        return AppHelper::sanitizeArray($response['result']);
    }
}
