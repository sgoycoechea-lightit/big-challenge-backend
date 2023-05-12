<?php

declare(strict_types=1);

namespace App\Exceptions;

use Flugg\Responder\Exceptions\Http\HttpException;

class IncorrectCredentialsException extends HttpException
{
    protected $status = 401;

    protected $errorCode = 'CREDENTIAL_INCORRECT';

    protected $message = 'The provided credentials are incorrect.';
}
