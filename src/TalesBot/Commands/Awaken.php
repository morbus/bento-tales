<?php

declare(strict_types=1);

namespace TalesBot\Commands;

use Discord\Builders\MessageBuilder;
use Discord\Parts\Interactions\Interaction;
use TalesBot\Attributes\Command;

/**
 * Find an agreeable piece of ground and strive to strive.
 */
#[Command]
class Awaken implements CommandInterface
{
    /**
     * Return information about the command.
     */
    public function getInfo(): array
    {
        return [
            'name' => 'awaken',
            'description' => 'Find an agreeable piece of ground and strive to strive.',
        ];
    }

    /**
     * Return the command's autocomplete suggestions.
     */
    public function autocomplete(Interaction $interaction): void
    {
    }

    /**
     * Handle the command and optionally respond to the user.
     */
    public function handle(Interaction $interaction): void
    {
        $interaction->respondWithMessage(MessageBuilder::new()->setContent('Pong!'));
    }
}
