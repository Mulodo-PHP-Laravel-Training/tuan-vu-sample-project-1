<?php

namespace App\Exceptions;


class ValidationException extends ApiException
{
    /**
     * @var integer
     */
    protected $statusCode = 400;
}