<?php

namespace Domain\Senior\Exceptions;

use Illuminate\Validation\ValidationException;

class SeniorSessionExpiredException extends ValidationException
{
    public $redirectTo = '/login';
}
