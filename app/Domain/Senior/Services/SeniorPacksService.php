<?php

namespace Domain\Senior\Services;

use App\Helpers\AppHelper;
use App\Models\Pack;
use App\Models\User;
use Domain\Integrations\ExecuteSeniorSoap;
use Domain\Integrations\SeniorSoapClient;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;

readonly class SeniorPacksService
{

    /**
     * @return array[]
     * @throws ValidationException
     */
    public function list(User $user, $filters): array
    {
        list($company, $branch) = AppHelper::fetchCompanyBranch();
        $resource = AppHelper::fetchResource();

        if (!$company || !$branch) {
            return [];
        }

        $soap = new SeniorSoapClient('sapiens_Synccom_Soeltech_Pack?wsdl');


        $start_date = date("d/m/Y", strtotime($filters['start_date']));
        $end_date = date("d/m/Y", strtotime($filters['end_date']));

        $response = (new ExecuteSeniorSoap())->execute($soap,
            'ListarPack',
            [
                Crypt::decrypt($user->getAttribute('user')),
                Crypt::decrypt($user->getAttribute('password')),
                0,
                [
                    "codEmp" => $company,
                    "codFil" => $branch,
                    "codCre" => $resource,
                    "finPack" => 2,
                    "datIni" => $start_date,
                    "datFin" => $end_date,
                ],
            ],
        );

        if(isset($response['result'])) {
            $response['result'] = AppHelper::sanitizeArray($response['result']);
        }

        $packs = [];
        foreach ($response['result'] ?? [] as $result) {
            $packs[] = Pack::make($result['packs']);
        }

        return $packs;
    }

    public function appointment($user, $idPack): array
    {
        list($company, $branch) = AppHelper::fetchCompanyBranch();

        $soap = new SeniorSoapClient('sapiens_Synccom_br_soelx_apontamento_producao?wsdl');

        $response = (new ExecuteSeniorSoap())->execute($soap,
            'ApontarProducao',
            [
                Crypt::decrypt($user->getAttribute('user')),
                Crypt::decrypt($user->getAttribute('password')),
                0,
                [
                    "codEmp" => $company,
                    "idPack" => $idPack,
                ],
            ],
        );


        return $response;
    }
}
