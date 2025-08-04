<?php

namespace Domain\Senior\Services;

use App\Helpers\AppHelper;
use App\Models\ProductionOrder;
use App\Models\User;
use Carbon\Carbon;
use Domain\Integrations\ExecuteSeniorSoap;
use Domain\Integrations\SeniorSoapClient;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;

readonly class SeniorStoreProductionOrderService
{
    /**
     * @param User $user
     * @param ProductionOrder $productionOrder
     * @param string $codlot
     * @param string $datFab
     * @param int $qtdEmb
     * @param int $qtd
     * @return void
     * @throws ValidationException
     */
    public function generate(User $user, ProductionOrder $productionOrder, string $codlot, string $datFab, int $qtdEmb, int $qtd): void
    {
        list($company, $branch) = AppHelper::fetchCompanyBranch();

        if (!$company || !$branch) {
            return;
        }

        $carbon = Carbon::create($datFab);
        $diffInMinutes = Carbon::now()->diffInMinutes($carbon->startOfDay()) * -1;

        $product = $productionOrder->getAttribute('produtos');
        $productionData = [
            'codOpe' => AppHelper::fetchOperator(),
            'finPack' => 2,
            'codEmp' => $company,
            'codOri' => $productionOrder->getAttribute('cod_ori'),
            'numOrp' => $productionOrder->getAttribute('num_orp'),
            'itensPack' => [
                'codPro' => $product['codPro'],
                'codDer' => $product['codDer'],
                'qtdIte' => $qtdEmb,
                'codLot' => $codlot,
                'horFab' => $diffInMinutes,
                'datFab' => $carbon->format('d/m/Y'),
            ],
        ];

        for ($i = 0; $i < $qtd; $i++) {
            (new ExecuteSeniorSoap())->execute(new SeniorSoapClient('sapiens_Synccom_Soeltech_Pack?wsdl'),
                'Gerar',
                [
                    Crypt::decrypt($user->getAttribute('user')),
                    Crypt::decrypt($user->getAttribute('password')),
                    0,
                    $productionData
                ],
            );
        }
    }
}
