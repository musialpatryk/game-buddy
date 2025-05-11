<?php

namespace App\SharedKernel\Exception;

use Phpro\ApiProblem\Exception\ApiProblemException;
use Phpro\ApiProblem\Http\BadRequestProblem;

class InvalidPayload extends ApiProblemException
{
    public function __construct()
    {
        parent::__construct(
            new BadRequestProblem('Invalid payload'),
        );
    }
}