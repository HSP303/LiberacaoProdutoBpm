<?php

namespace Domain\Senior\Services;

use App\Helpers\AppHelper;
use App\Models\User;
use Domain\Integrations\ExecuteSeniorSoap;
use Domain\Integrations\SeniorSoapClient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;

readonly class SeniorCompanyBranchService
{

    /**
     * @return array[]
     */
    public function list(User $user): array
    {
        return Cache::remember(
            'senior_company_branches',
            null,
            fn() => $this->retrieveCompanyBranches($user)
        );
    }

    /**
     * @param User $user
     * @return array|array[]
     * @throws ValidationException
     */
    private function retrieveCompanyBranches(User $user): array
    {
        $soap = new SeniorSoapClient('sapiens_Synccom_br_soelx_apontamento_producao?wsdl');

        $response = (new ExecuteSeniorSoap())->execute($soap,
            'retornarEmpresaFilial',
            [
                Crypt::decrypt($user->getAttribute('user')),
                Crypt::decrypt($user->getAttribute('password')),
                0,
                [],
            ],
        );

        foreach ($response['empresas'] as &$empresa) {
            if (isset($empresa['filiais']) && !is_array(reset($empresa['filiais']))) {
                $empresa['filiais'] = [$empresa['filiais']];
            }
        }

        return AppHelper::sanitizeArray($response['empresas']);
    }
}
