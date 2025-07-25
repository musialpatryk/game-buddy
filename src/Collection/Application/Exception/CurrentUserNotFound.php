<?php

namespace App\Collection\Application\Exception;

use Phpro\ApiProblem\Exception\ApiProblemException;
use Phpro\ApiProblem\Http\NotFoundProblem;

class CurrentUserNotFound extends ApiProblemException
{
    public function __construct()
    {
        parent::__construct(
            new NotFoundProblem('Current user not found!'),
        );
    }
}