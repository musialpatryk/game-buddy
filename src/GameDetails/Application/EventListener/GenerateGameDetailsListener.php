<?php

namespace App\GameDetails\Application\EventListener;

use App\Game\Application\Event\GameCreated;
use App\Game\Application\Event\GameEvent;
use App\Game\Application\Event\GameUpdated;
use App\GameDetails\Application\Service\GameDetailsService;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: GameCreated::class)]
#[AsEventListener(event: GameUpdated::class)]
final readonly class GenerateGameDetailsListener
{
    public function __construct(
        private GameDetailsService $gameDetailsService,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(GameEvent $event): void
    {
        $gameDetails = $this->gameDetailsService->generate(
            $event->getGame()->getId()
        );
        if (!$gameDetails) {
            $this->logger->warning(
                'Could not generate game details for based on event.',
                [
                    'event' => get_class($event),
                    'game_id' => $event->getGame()->getId(),
                ]
            );
            return;
        }

        $this->gameDetailsService->upsert($gameDetails);
    }
}