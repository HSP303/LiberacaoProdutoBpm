<?php

namespace Domain\Senior\Services;

use App\Models\User;
use Domain\Integrations\ExecuteSeniorSoap;
use Domain\Integrations\SeniorSoapClient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;

readonly class SeniorResourceCenterService
{

    /**
     * @return array[]
     */
    public function list(User $user): array
    {
        return Cache::remember(
            'senior_resource',
            null,
            fn() => $this->fetchResourceCenters($user)
        );
    }

    /**
     * @param User $user
     * @return mixed
     * @throws ValidationException
     */
    private function fetchResourceCenters(User $user): mixed
    {
        return Cache::remember(
            'senior_resource',
            null,
            fn() => $this->retrieveSeniorResourceCenters($user)
        );
    }

    /**
     * @param User $user
     * @return mixed
     * @throws ValidationException
     */
    private function retrieveSeniorResourceCenters(User $user): mixed
    {
        $soap = new SeniorSoapClient('sapiens_Synccom_br_soelx_apontamento_producao?wsdl');

        $response = (new ExecuteSeniorSoap())->execute($soap,
            'ListarCentroRecursos',
            [
                Crypt::decrypt($user->getAttribute('user')),
                Crypt::decrypt($user->getAttribute('password')),
                0,
                [],
            ],
        );

        return $response['centroRecursos'];
    }
}
