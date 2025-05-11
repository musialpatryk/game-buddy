<?php

namespace App\Game\Application\Exception;

use Phpro\ApiProblem\Exception\ApiProblemException;
use Phpro\ApiProblem\Http\NotFoundProblem;

class GameDoesNotExists extends ApiProblemException
{
    public function __construct()
    {
        parent::__construct(
            new NotFoundProblem('Game does not exists!'),
        );
    }
}