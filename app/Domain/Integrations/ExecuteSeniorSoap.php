<?php

namespace Domain\Integrations;

use Domain\Senior\Exceptions\IntegrationSoapSeniorException;
use Domain\Senior\Exceptions\SeniorSessionExpiredException;
use Domain\User\Services\LogoutUserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use stdClass;
use function str_contains;

class ExecuteSeniorSoap
{

    /**
     * @throws ValidationException
     */
    public function execute(SeniorSoapClient $seniorSoapClient, string $function, array $parameters): array
    {
        $response = $seniorSoapClient->{$function}(...$parameters);
        $this->handleErrors($response);

        return json_decode(json_encode($response), true);
    }


    /**
     * @param stdClass $response
     * @return RedirectResponse|null
     * @throws ValidationException
     */
    protected function handleErrors(stdClass $response): RedirectResponse|null
    {
        if (is_soap_fault($response)) {
            throw new IntegrationSoapSeniorException("Failure to consume Senior's soap: $response->faultcode - $response->faultstring");
        }

        if ($response->erroExecucao) {
            if (str_contains($response->erroExecucao, 'Credenciais inválidas')) {
                (new LogoutUserService())->logout();

                throw SeniorSessionExpiredException::withMessages([
                    'username' => "Sessão com a Senior expirou. Efetue login novamente.",
                ]);
            }

            throw new IntegrationSoapSeniorException("Failure to consume Senior's soap: $response->erroExecucao");
        }

        return null;
    }
}
