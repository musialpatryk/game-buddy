<?php

namespace App\Game\Application\Exception;

use Phpro\ApiProblem\Exception\ApiProblemException;
use Phpro\ApiProblem\Http\ConflictProblem;

class GameDoesNotExists extends ApiProblemException
{
    public function __construct()
    {
        parent::__construct(
            new ConflictProblem('Game does not exists!'),
        );
    }
}