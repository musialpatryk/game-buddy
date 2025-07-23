<?php

namespace App\GameDetails\Application\Exception;

use Phpro\ApiProblem\Exception\ApiProblemException;
use Phpro\ApiProblem\Http\NotFoundProblem;

class GameDetailsDoesNotExists extends ApiProblemException
{
    public function __construct()
    {
        parent::__construct(
            new NotFoundProblem('Game details does not exists!'),
        );
    }
}