<?php

namespace Domain\Integrations;

use Domain\Senior\Exceptions\IntegrationSoapSeniorException;
use SoapClient;

/**
 * @method genericConsult(string $string, string $string1, int $int, array $query)
 * @method authenticateJAAS($null, $null1, int $int, array $array)
 * @method ListarOperadores($null, $null1, int $int, array $array)
 */
class SeniorSoapClient extends SoapClient
{
    public function __construct(?string $wsdl, array $options = [])
    {
        if (!config('integration.senior')) {
            throw new IntegrationSoapSeniorException(400, 'Integration not configured');
        }

        parent::__construct(config('integration.senior') . '/' . $wsdl, $options);
    }
}
