<?php

namespace Domain\Senior\Services;

use App\Helpers\AppHelper;
use App\Models\User;
use Domain\Integrations\ExecuteSeniorSoap;
use Domain\Integrations\SeniorSoapClient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;

readonly class SeniorOperatorsService
{

    /**
     * @param User $user
     * @param int|null $numcad
     * @return array[]
     */
    public function list(User $user, ?int $numcad = null): array
    {
        return Cache::remember(
            'senior_operators',
            null,
            fn() => $this->fetchOperatorsByNumCad($user, $numcad)
        );
    }

    /**
     * @param User $user
     * @param int|null $numcad
     * @return array|array[]
     * @throws ValidationException
     */
    private function fetchOperatorsByNumCad(User $user, ?int $numcad): array
    {
        $soap = new SeniorSoapClient('sapiens_Synccom_br_soelx_apontamento_producao?wsdl');

        $response = (new ExecuteSeniorSoap())->execute($soap,
            'ListarOperadores',
            [
                Crypt::decrypt($user->getAttribute('user')),
                Crypt::decrypt($user->getAttribute('password')),
                0,
                [
                    "numCad" => $numcad
                ],
            ],
        );

        return AppHelper::sanitizeArray($response['operadores']);
    }

}
