<?php

namespace Domain\Senior\Services;

use App\Helpers\AppHelper;
use App\Models\Pack;
use App\Models\User;
use Domain\Integrations\ExecuteSeniorSoap;
use Domain\Integrations\SeniorSoapClient;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;

readonly class SeniorStorePacksService
{

    /**
     * @return array[]
     * @throws ValidationException
     */
    public function generate(User $user, Pack $pack): array
    {
        list($company, $branch) = AppHelper::fetchCompanyBranch();

        if (!$company || !$branch) {
            return [];
        }

        $soap = new SeniorSoapClient('sapiens_Synccom_Soeltech_Pack?wsdl');

        $response = (new ExecuteSeniorSoap())->execute($soap,
            'GerarPack',
            [
                Crypt::decrypt($user->getAttribute('user')),
                Crypt::decrypt($user->getAttribute('password')),
                0,
                $pack->toArray(),
            ],
        );

        $packs = [];
        foreach ($response['result'] ?? [] as $result) {
            $packs[] = Pack::make($result['packs']);
        }

        return $packs;
    }

}
