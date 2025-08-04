<?php

namespace Domain\Senior\Services;

use App\Helpers\AppHelper;
use App\Models\ProductionOrder;
use App\Models\User;
use Domain\Integrations\ExecuteSeniorSoap;
use Domain\Integrations\SeniorSoapClient;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;

readonly class SeniorFinalizeProductionOrdersService
{

    /**
     * @param User $user
     * @param ProductionOrder $productionOrder
     * @return array
     * @throws ValidationException
     */
    public function finalizeProductionOrder(User $user, ProductionOrder $productionOrder): array
    {
        $soap = new SeniorSoapClient('sapiens_Synccom_br_soelx_apontamento_producao?wsdl');

        $response = (new ExecuteSeniorSoap())->execute($soap,
            'FinalizarOp',
            [
                Crypt::decrypt($user->getAttribute('user')),
                Crypt::decrypt($user->getAttribute('password')),
                0,
                [
                    "codEmp" => $productionOrder->getAttribute('cod_emp'),
                    "codOri" => $productionOrder->getAttribute('cod_ori'),
                    "numOrp" => $productionOrder->getAttribute('num_orp'),
                    "codOpe" => AppHelper::fetchOperator(),
                ],
            ],
        );

        if ($response['statusProcesso'] === 0) {
            return [$response['mensagemProcesso'], 'error'];
        }

        return [$response['mensagemProcesso'], 'success'];
    }
}
