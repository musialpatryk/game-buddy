<?php

namespace App\Game\Application\Event;

use App\Game\Domain\Game;
use Symfony\Contracts\EventDispatcher\Event;

abstract class GameEvent extends Event
{
    public function __construct(private readonly Game $game)
    {
    }

    public function getGame(): Game
    {
        return $this->game;
    }
}