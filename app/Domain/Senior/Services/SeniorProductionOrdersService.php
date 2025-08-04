<?php

namespace Domain\Senior\Services;

use App\Helpers\AppHelper;
use App\Models\ProductionOrder;
use App\Models\User;
use Domain\Integrations\ExecuteSeniorSoap;
use Domain\Integrations\SeniorSoapClient;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;
use function json_encode;

readonly class SeniorProductionOrdersService
{

    /**
     * @param User $user
     * @return void
     * @throws ValidationException|Throwable
     */
    public function upsert(User $user): void
    {
        $response = $this->fetchProductionOrders($user);

        try {
            DB::beginTransaction();

            $productionOrders = $this->processProductionOrders($response['ordensProducao'] ?? [], $user);
            $this->deleteUnwantedOrders($user, $productionOrders);

            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw $throwable;
        }

    }

    /**
     * @param array $productionsOrders
     * @param User $user
     * @return array
     */
    private function processProductionOrders(array $productionsOrders, User $user): array
    {
        $productionOrders = [];
        foreach (AppHelper::sanitizeArray($productionsOrders) as $productionOrder) {
            $productionOrders[] = [
                "cod_emp" => $productionOrder['codEmp'],
                "cod_ori" => $productionOrder['codOri'],
                "num_orp" => $productionOrder['numOrp'],
                "produtos" => json_encode($productionOrder['produtos'] ?? []),
                "apontamentos" => json_encode( isset($productionOrder['apontamentos']) ? AppHelper::sanitizeArray($productionOrder['apontamentos']) : []),
                "user_id" => $user->getKey(),
            ];
        }

        ProductionOrder::upsert(
            $productionOrders,
            ['cod_emp', 'cod_ori', 'num_orp', 'user_id'],
            ['produtos', 'apontamentos']
        );
        return $productionOrders;
    }

    /**
     * @param User $user
     * @return array
     * @throws ValidationException
     */
    private function fetchProductionOrders(User $user): array
    {
        $soap = new SeniorSoapClient('sapiens_Synccom_br_soelx_apontamento_producao?wsdl');

        return (new ExecuteSeniorSoap())->execute($soap,
            'ListarOrdensProducao',
            [
                Crypt::decrypt($user->getAttribute('user')),
                Crypt::decrypt($user->getAttribute('password')),
                0,
                [
                    "codCre" => AppHelper::fetchResource(),
                    "codEmp" => AppHelper::fetchCompanyBranch()[0]
                ],
            ],
        );
    }

    /**
     * @param User $user
     * @param array $productionOrders
     * @return void
     */
    private function deleteUnwantedOrders(User $user, array $productionOrders): void
    {
        $uniqueKeys = collect($productionOrders)->map(function ($order) {
            return [
                'cod_emp' => $order['cod_emp'],
                'cod_ori' => $order['cod_ori'],
                'num_orp' => $order['num_orp'],
                'user_id' => $order['user_id'],
            ];
        });

        ProductionOrder::where('user_id', $user->getKey())
            ->whereNotIn('id', ProductionOrder::select('id')
                ->where('user_id', $user->getKey())
                ->where(function ($query) use ($uniqueKeys) {
                    foreach ($uniqueKeys as $key) {
                        $query->orWhere(function ($q) use ($key) {
                            $q->where('cod_emp', $key['cod_emp'])
                                ->where('cod_ori', $key['cod_ori'])
                                ->where('num_orp', $key['num_orp'])
                                ->where('user_id', $key['user_id']);
                        });
                    }
                })->pluck('id')
            )
            ->delete();
    }
}
